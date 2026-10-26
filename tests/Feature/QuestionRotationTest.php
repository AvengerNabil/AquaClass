<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\LearningActivity;
use App\Models\QuizSession;
use App\Models\User;
use App\Services\QuizSessionService;
use App\Services\PointService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionRotationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_activity_provides_10_15_and_20_questions(): void
    {
        $service = app(QuizSessionService::class);
        $activity = LearningActivity::first();
        $this->assertNotNull($activity);

        $poolCount = $activity->questions()->count();
        $this->assertGreaterThanOrEqual(10, $poolCount);

        foreach ([10, 15, 20] as $len) {
            $data = $service->createSession($activity, null, $len);
            $expected = min($len, $poolCount);
            $this->assertCount($expected, $data['questions']);

            // Ensure no duplicate questions in session
            $ids = array_column($data['questions']->toArray(), 'id');
            $this->assertSame(count($ids), count(array_unique($ids)));
        }
    }

    public function test_dynamic_question_rotation_prefers_unseen_questions(): void
    {
        $service = app(QuizSessionService::class);
        $pointService = app(PointService::class);
        $activity = LearningActivity::first();
        $student = User::where('role', 'student')->first() ?? User::first();

        // First session (10 questions)
        $sessionA = $service->createSession($activity, $student, 10);
        $idsA = array_column($sessionA['questions']->toArray(), 'id');
        $this->assertCount(10, $idsA);

        // Complete session A
        $answersA = [];
        foreach ($sessionA['questions'] as $q) {
            $answersA[$q['id']] = $q['correct_answer'];
        }
        $service->evaluateSession($sessionA['session'], $answersA, $student, $pointService);

        // Second session (10 questions)
        $sessionB = $service->createSession($activity, $student, 10);
        $idsB = array_column($sessionB['questions']->toArray(), 'id');
        $this->assertCount(10, $idsB);

        // If pool has 25 questions, all 10 questions in session B should be unseen (0 overlap with session A)
        $overlap = array_intersect($idsA, $idsB);
        $this->assertCount(0, $overlap, 'Session B should feature 10 completely fresh unseen questions from the 25-question bank');
    }

    public function test_options_are_shuffled_and_answers_accurate(): void
    {
        $service = app(QuizSessionService::class);
        $activity = LearningActivity::first();
        $sessionData = $service->createSession($activity, null, 10);

        foreach ($sessionData['questions'] as $q) {
            $this->assertCount(4, $q['options']);
            $hasCorrect = false;
            foreach ($q['options'] as $opt) {
                if ($opt['option_text'] === $q['correct_answer'] && $opt['is_correct']) {
                    $hasCorrect = true;
                }
            }
            $this->assertTrue($hasCorrect, "Correct answer must be present among options for question ID {$q['id']}");
        }
    }

    public function test_session_submission_is_idempotent(): void
    {
        $service = app(QuizSessionService::class);
        $pointService = app(PointService::class);
        $activity = LearningActivity::first();
        $student = User::where('role', 'student')->first() ?? User::first();

        $sessionData = $service->createSession($activity, $student, 10);
        $answers = [];
        foreach ($sessionData['questions'] as $q) {
            $answers[$q['id']] = $q['correct_answer'];
        }

        $res1 = $service->evaluateSession($sessionData['session'], $answers, $student, $pointService);
        $this->assertFalse($res1['already_completed']);
        $this->assertEquals(10, $res1['correct_count']);

        // Duplicate submission prevention
        $res2 = $service->evaluateSession($sessionData['session'], $answers, $student, $pointService);
        $this->assertTrue($res2['already_completed']);
        $this->assertNull($res2['point_result']);
    }
}
