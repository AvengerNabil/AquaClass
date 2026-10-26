<?php

namespace App\Http\Controllers;

use App\Models\LearningActivity;
use App\Models\ActivityAttempt;
use App\Models\QuizSession;
use App\Services\PointService;
use App\Services\QuizSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = LearningActivity::where('active', true)
            ->with(['subject', 'gradeLevel', 'questions']);

        if ($request->filled('subject')) {
            $query->whereHas('subject', fn($q) => $q->where('slug', $request->subject));
        }

        if ($request->filled('grade')) {
            $query->whereHas('gradeLevel', fn($q) => $q->where('name', $request->grade));
        }

        $activities = $query->paginate(12);

        return view('activities.index', [
            'activities' => $activities,
            'currentSubject' => $request->subject,
            'currentGrade' => $request->grade,
        ]);
    }

    public function show(Request $request, LearningActivity $activity, QuizSessionService $sessionService)
    {
        $activity->load(['subject', 'gradeLevel', 'objective']);
        $user = Auth::user();

        // Target session length: 10, 15, or 20 (defaults to 10)
        $requestedLength = (int) $request->input('length', 10);

        $sessionData = $sessionService->createSession($activity, $user, $requestedLength);

        return view('activities.play', [
            'activity' => $activity,
            'quizSession' => $sessionData['session'],
            'sessionQuestions' => $sessionData['questions'],
            'sessionLength' => $sessionData['session_length'],
            'availableTotal' => $sessionData['available_total'],
        ]);
    }

    public function submit(Request $request, LearningActivity $activity, QuizSessionService $sessionService, PointService $pointService)
    {
        $user = Auth::user();
        $sessionToken = $request->input('session_token');

        if ($sessionToken) {
            $session = QuizSession::where('session_token', $sessionToken)
                ->where('activity_id', $activity->id)
                ->first();

            if ($session) {
                $eval = $sessionService->evaluateSession(
                    $session,
                    $request->input('answers', []),
                    $user,
                    $pointService
                );

                return response()->json($eval);
            }
        }

        // Fallback for legacy requests without session_token
        $answers = $request->input('answers', []);
        $totalQuestions = $activity->questions()->count();
        $correctCount = 0;
        $earnedPoints = 0;
        $details = [];

        foreach ($activity->questions as $question) {
            $userAns = trim($answers[$question->id] ?? '');
            $isCorrect = (strcasecmp($userAns, trim($question->correct_answer)) === 0);

            if ($isCorrect) {
                $correctCount++;
                $earnedPoints += $question->points;
            }

            if ($user) {
                ActivityAttempt::create([
                    'user_id' => $user->id,
                    'activity_id' => $activity->id,
                    'question_id' => $question->id,
                    'answer_data' => ['selected' => $userAns],
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? $question->points : 0,
                    'completed_at' => now(),
                ]);
            }

            $details[$question->id] = [
                'correct' => $isCorrect,
                'user_answer' => $userAns,
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
            ];
        }

        $pointResult = null;
        if ($user && $earnedPoints > 0) {
            $pointResult = $pointService->awardPoints(
                $user,
                $earnedPoints,
                'activity_completion',
                'LearningActivity',
                $activity->id,
                "Completed: {$activity->title} ({$correctCount}/{$totalQuestions} correct)"
            );
        }

        return response()->json([
            'success' => true,
            'is_guest' => !$user,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'points_earned' => $earnedPoints,
            'point_result' => $pointResult,
            'details' => $details,
        ]);
    }
}
