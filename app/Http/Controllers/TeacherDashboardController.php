<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Subject;
use App\Models\LearningObjective;
use App\Models\LearningActivity;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $classrooms = Classroom::where('teacher_id', $teacher->id)
            ->with(['gradeLevel', 'students.studentProfile', 'students.fish.stage', 'assignments'])
            ->get();

        $activeClassroom = $classrooms->first();

        // Pending submissions across teacher's classrooms
        $pendingSubmissions = collect();
        if ($activeClassroom) {
            $pendingSubmissions = Submission::whereHas('assignment', function ($q) use ($activeClassroom) {
                $q->where('classroom_id', $activeClassroom->id);
            })
            ->where('status', 'submitted')
            ->with(['user.studentProfile', 'user.fish', 'assignment.subject'])
            ->latest('submitted_at')
            ->get();
        }

        $allSubjects = Subject::all();
        $allObjectives = LearningObjective::all();

        return view('teacher.dashboard', [
            'teacher' => $teacher,
            'classrooms' => $classrooms,
            'classroom' => $activeClassroom,
            'pendingSubmissions' => $pendingSubmissions,
            'subjects' => $allSubjects,
            'objectives' => $allObjectives,
        ]);
    }

    public function classroomTank(Classroom $classroom)
    {
        $this->authorizeAccess($classroom);

        $classroom->load(['students.studentProfile', 'students.fish.stage', 'aquarium']);

        return view('teacher.classroom-tank', [
            'classroom' => $classroom,
            'students' => $classroom->students,
        ]);
    }

    public function assignments(Classroom $classroom)
    {
        $this->authorizeAccess($classroom);

        $assignments = Assignment::where('classroom_id', $classroom->id)
            ->with(['subject', 'submissions.user.studentProfile'])
            ->latest()
            ->paginate(10);

        return view('teacher.assignments', [
            'classroom' => $classroom,
            'assignments' => $assignments,
        ]);
    }

    public function createAssignment(Classroom $classroom)
    {
        $this->authorizeAccess($classroom);

        $subjects = Subject::all();
        $objectives = LearningObjective::where('grade_level_id', $classroom->grade_level_id)->get();
        $activities = LearningActivity::where('grade_level_id', $classroom->grade_level_id)->get();

        return view('teacher.assignment-create', [
            'classroom' => $classroom,
            'subjects' => $subjects,
            'objectives' => $objectives,
            'activities' => $activities,
        ]);
    }

    public function storeAssignment(Request $request, Classroom $classroom)
    {
        $this->authorizeAccess($classroom);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:homework,activity,reading,project,challenge',
            'subject_id' => 'required|exists:subjects,id',
            'objective_id' => 'nullable|exists:learning_objectives,id',
            'activity_id' => 'nullable|exists:learning_activities,id',
            'points' => 'required|integer|min:5|max:100',
            'due_date' => 'nullable|date',
            'allow_late' => 'boolean',
        ]);

        $assignment = Assignment::create([
            'classroom_id' => $classroom->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'subject_id' => $validated['subject_id'],
            'activity_id' => $validated['activity_id'] ?? null,
            'points' => $validated['points'],
            'due_date' => $validated['due_date'] ?? now()->addDays(3),
            'status' => 'published',
            'allow_late' => $request->boolean('allow_late'),
            'created_by' => Auth::id(),
        ]);

        if (!empty($validated['objective_id'])) {
            $assignment->objectives()->sync([$validated['objective_id']]);
        }

        return redirect()->route('teacher.dashboard')
            ->with('success', "✨ Assignment '{$assignment->title}' published to {$classroom->name}!");
    }

    public function reviewSubmission(Request $request, Submission $submission, PointService $pointService)
    {
        $assignment = $submission->assignment;
        $this->authorizeAccess($assignment->classroom);

        $validated = $request->validate([
            'status' => 'required|in:approved,needs_revision,rejected',
            'feedback' => 'nullable|string|max:1000',
            'points_awarded' => 'required|integer|min:0|max:' . $assignment->points,
        ]);

        $submission->status = $validated['status'];
        $submission->feedback = $validated['feedback'] ?? null;
        $submission->points_awarded = $validated['points_awarded'];
        $submission->reviewed_at = now();
        $submission->reviewer_id = Auth::id();

        // Award points if approved and not yet credited
        if ($validated['status'] === 'approved' && !$submission->points_credited && $validated['points_awarded'] > 0) {
            $pointService->awardPoints(
                $submission->user,
                $validated['points_awarded'],
                'homework_completion',
                'Assignment',
                $assignment->id,
                "Reviewed: {$assignment->title}",
                Auth::id()
            );
            $submission->points_credited = true;
        }

        $submission->save();

        return redirect()->back()->with('success', '🌟 Submission reviewed and points credited to student!');
    }

    public function awardCustomPoints(Request $request, PointService $pointService)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1|max:100',
            'reason' => 'required|string|max:255',
        ]);

        $student = User::findOrFail($validated['user_id']);

        $res = $pointService->awardPoints(
            $student,
            $validated['points'],
            'teacher_award',
            'TeacherReward',
            Auth::id(),
            $validated['reason'],
            Auth::id()
        );

        return redirect()->back()->with('success', "🎉 Awarded {$validated['points']} points to {$student->name} for '{$validated['reason']}'!");
    }

    public function exportClassroomCsv(Classroom $classroom)
    {
        $this->authorizeAccess($classroom);

        $students = $classroom->students()->with(['studentProfile', 'fish.stage', 'pointTransactions'])->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"aquaclass-{$classroom->name}-roster.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($students, $classroom) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student ID', 'Name', 'Email', 'Total Growth Points', 'Daily Streak', 'Fish Name', 'Fish Species', 'Growth Stage', 'Joined Date']);

            foreach ($students as $student) {
                fputcsv($handle, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->studentProfile?->total_points ?? 0,
                    $student->studentProfile?->current_streak ?? 0,
                    $student->fish?->name ?? 'None',
                    $student->fish?->species ?? 'None',
                    $student->fish?->stage?->display_name ?? 'Tiny Fry',
                    $student->created_at->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    protected function authorizeAccess(Classroom $classroom)
    {
        if (Auth::user()->isTeacher() && $classroom->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to classroom.');
        }
    }
}
