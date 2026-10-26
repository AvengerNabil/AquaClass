<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\LearningActivity;
use App\Services\PointService;

class AquaClassCoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_landing_page_loads_with_stages(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Learn More.');
        $response->assertSee('Grow Together.');
    }

    public function test_demo_switcher_switches_to_teacher(): void
    {
        $response = $this->get('/demo/teacher');
        $response->assertRedirect('/teacher/dashboard');
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isTeacher());
    }

    public function test_demo_switcher_switches_to_student(): void
    {
        $response = $this->get('/demo/leo');
        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isStudent());
    }

    public function test_point_service_advances_fish_growth(): void
    {
        $student = User::where('email', 'kai@aquaclass.com')->first();
        $pointService = app(PointService::class);

        // Kai has 95 pts (little_swimmer). Award 100 points to bring him to 195 pts (reef_explorer, min 150)
        $res = $pointService->awardPoints($student, 100, 'homework_completion', 'Assignment', 1, 'Great work');

        $this->assertTrue($res['success']);
        $this->assertEquals(195, $res['total_points']);
        $student->refresh();
        $this->assertEquals('reef_explorer', $student->fish->stage->name);
    }

    public function test_interactive_activity_submission(): void
    {
        $student = User::where('email', 'leo@aquaclass.com')->first();
        $this->actingAs($student);

        $activity = LearningActivity::where('title', 'Coral Reef Math: Addition Adventure')->first();
        $q1 = $activity->questions->first();

        $response = $this->postJson("/api/activities/{$activity->id}/submit", [
            'answers' => [
                $q1->id => $q1->correct_answer,
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_student_can_submit_homework(): void
    {
        $student = User::where('email', 'kai@aquaclass.com')->first();
        $assignment = Assignment::first();

        $this->actingAs($student);
        $response = $this->post("/student/assignments/{$assignment->id}/submit", [
            'content' => 'My favorite sea creature is the colorful mandarin fish that hides in soft coral.'
        ]);

        $response->assertRedirect("/student/assignments/{$assignment->id}");
        $this->assertDatabaseHas('submissions', [
            'assignment_id' => $assignment->id,
            'user_id' => $student->id,
            'status' => 'submitted',
        ]);
    }

    public function test_teacher_can_export_roster_csv(): void
    {
        $teacher = User::where('email', 'teacher@aquaclass.com')->first();
        $classroom = Classroom::where('teacher_id', $teacher->id)->first();

        $this->actingAs($teacher);
        $response = $this->get("/teacher/classrooms/{$classroom->id}/export");

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }

    public function test_invalid_classroom_join_code_fails(): void
    {
        $student = User::where('email', 'kai@aquaclass.com')->first();
        $this->actingAs($student);

        $response = $this->post('/join', [
            'join_code' => 'BADCODE'
        ]);

        $response->assertSessionHasErrors('join_code');
    }
}
