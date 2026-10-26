<?php

namespace App\Services;

use App\Models\LearningActivity;
use App\Models\ActivityQuestion;
use App\Models\ActivityAttempt;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class QuizSessionService
{
    /**
     * Supported session lengths
     */
    public const SESSION_LENGTHS = [10, 15, 20];
    public const DEFAULT_SESSION_LENGTH = 10;

    /**
     * Create a new dynamic quiz session with repeat-avoidance and rotation
     */
    public function createSession(LearningActivity $activity, ?User $user = null, int $requestedLength = self::DEFAULT_SESSION_LENGTH): array
    {
        // 1. Sanitize requested session length
        if (!in_array($requestedLength, self::SESSION_LENGTHS)) {
            $requestedLength = self::DEFAULT_SESSION_LENGTH;
        }

        // 2. Fetch all available questions with options for this activity
        $allQuestions = $activity->questions()
            ->with(['options'])
            ->get();

        $totalAvailable = $allQuestions->count();
        if ($totalAvailable === 0) {
            throw new \RuntimeException("No questions available for activity #{$activity->id}");
        }

        // Session length cannot exceed available questions
        $sessionLength = min($requestedLength, $totalAvailable);

        // 3. Select questions based on student attempt history (Repeat Avoidance)
        $selectedQuestions = $this->selectQuestionsWithRotation($allQuestions, $activity, $user, $sessionLength);

        // 4. Randomize question display order
        $shuffledQuestions = $selectedQuestions->shuffle()->values();

        // 5. Create persistent QuizSession record in Supabase
        $session = QuizSession::create([
            'user_id' => $user?->id,
            'session_token' => Str::random(40),
            'activity_id' => $activity->id,
            'session_length' => $sessionLength,
            'question_ids' => $shuffledQuestions->pluck('id')->values()->all(),
            'total_questions' => $shuffledQuestions->count(),
            'completed' => false,
        ]);

        // 6. Format questions with randomized option order
        $formattedQuestions = $shuffledQuestions->map(function (ActivityQuestion $q) {
            $shuffledOptions = $q->options->shuffle()->values()->map(function ($opt, $idx) {
                return [
                    'id' => $opt->id,
                    'option_text' => $opt->option_text,
                    'is_correct' => (bool) $opt->is_correct,
                    'sort_order' => $idx + 1,
                ];
            });

            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'type' => $q->type,
                'points' => $q->points,
                'question_data' => $q->question_data,
                'options' => $shuffledOptions,
                'correct_answer' => $q->correct_answer,
                'explanation' => $q->explanation,
            ];
        });

        return [
            'session' => $session,
            'questions' => $formattedQuestions,
            'session_length' => $sessionLength,
            'available_total' => $totalAvailable,
        ];
    }

    /**
     * Smart rotation logic:
     * Priority 1: Unseen questions (never attempted by this user)
     * Priority 2: Least recently attempted questions (oldest completed_at)
     * Fallback: Shuffle and pick
     */
    protected function selectQuestionsWithRotation(Collection $allQuestions, LearningActivity $activity, ?User $user, int $targetCount): Collection
    {
        if (!$user) {
            // Guest mode: simply shuffle the full pool and pick target count
            return $allQuestions->shuffle()->take($targetCount)->values();
        }

        // Query user's attempt history for this activity
        $attemptStats = ActivityAttempt::where('user_id', $user->id)
            ->where('activity_id', $activity->id)
            ->whereNotNull('question_id')
            ->select('question_id', DB::raw('MAX(completed_at) as last_seen_at'), DB::raw('COUNT(*) as attempt_count'))
            ->groupBy('question_id')
            ->get()
            ->keyBy('question_id');

        $unseen = collect();
        $seen = collect();

        foreach ($allQuestions as $question) {
            if ($attemptStats->has($question->id)) {
                $stat = $attemptStats->get($question->id);
                $seen->push([
                    'question' => $question,
                    'last_seen_at' => $stat->last_seen_at,
                    'attempt_count' => $stat->attempt_count,
                ]);
            } else {
                $unseen->push($question);
            }
        }

        $selected = collect();

        // 1. Take unseen questions first (randomized)
        if ($unseen->isNotEmpty()) {
            $unseenShuffled = $unseen->shuffle();
            $takeUnseen = $unseenShuffled->take($targetCount);
            $selected = $selected->merge($takeUnseen);
        }

        // 2. If we still need more questions, take least recently seen (sorted oldest first)
        $needed = $targetCount - $selected->count();
        if ($needed > 0 && $seen->isNotEmpty()) {
            // Sort by last_seen_at ascending (oldest first)
            $seenSorted = $seen->sortBy('last_seen_at')->values();

            // To avoid exact deterministic order when dates are similar, group by date or chunk and shuffle slightly
            $takeSeen = $seenSorted->take($needed)->pluck('question');
            $selected = $selected->merge($takeSeen);
        }

        return $selected->values();
    }

    /**
     * Evaluate submitted answers for a quiz session
     */
    public function evaluateSession(QuizSession $session, array $submittedAnswers, ?User $user, PointService $pointService): array
    {
        if ($session->completed) {
            return [
                'already_completed' => true,
                'success' => true,
                'is_guest' => !$user,
                'correct_count' => $session->correct_count,
                'total_questions' => $session->total_questions,
                'points_earned' => $session->earned_points,
                'point_result' => null,
                'details' => [],
            ];
        }

        $activity = $session->activity;
        $questionIds = $session->question_ids ?? [];
        $questions = ActivityQuestion::whereIn('id', $questionIds)->get()->keyBy('id');

        $totalQuestions = count($questionIds);
        $correctCount = 0;
        $earnedPoints = 0;
        $details = [];

        foreach ($questionIds as $qId) {
            $question = $questions->get($qId);
            if (!$question) continue;

            $userAns = trim($submittedAnswers[$qId] ?? '');
            $isCorrect = (strcasecmp($userAns, trim($question->correct_answer)) === 0);

            if ($isCorrect) {
                $correctCount++;
                $earnedPoints += $question->points;
            }

            // Record student attempt if logged in
            if ($user) {
                ActivityAttempt::create([
                    'user_id' => $user->id,
                    'activity_id' => $activity->id,
                    'quiz_session_id' => $session->id,
                    'question_id' => $question->id,
                    'answer_data' => ['selected' => $userAns],
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? $question->points : 0,
                    'completed_at' => now(),
                ]);
            }

            $details[$qId] = [
                'correct' => $isCorrect,
                'user_answer' => $userAns,
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
            ];
        }

        // Award points if logged in and earned points > 0
        $pointResult = null;
        if ($user && $earnedPoints > 0) {
            $pointResult = $pointService->awardPoints(
                $user,
                $earnedPoints,
                'activity_completion',
                'QuizSession',
                $session->id,
                "Completed: {$activity->title} ({$correctCount}/{$totalQuestions} correct, {$session->session_length}Q session)"
            );
        }

        // Mark session as completed
        $session->update([
            'answers' => $submittedAnswers,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'earned_points' => $earnedPoints,
            'completed' => true,
            'completed_at' => now(),
        ]);

        return [
            'already_completed' => false,
            'success' => true,
            'is_guest' => !$user,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'points_earned' => $earnedPoints,
            'point_result' => $pointResult,
            'details' => $details,
        ];
    }
}
