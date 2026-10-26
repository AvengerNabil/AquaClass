<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\LearningActivity;
use App\Models\FishDecoration;
use App\Models\AquariumDecoration;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['studentProfile', 'fish.stage', 'fish.decorations.decoration', 'achievements', 'classrooms']);

        $classroom = $user->classrooms->first();
        $assignments = collect();

        if ($classroom) {
            $assignments = Assignment::where('classroom_id', $classroom->id)
                ->where('status', 'published')
                ->with(['subject', 'submissions' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->orderBy('due_date')
                ->take(5)
                ->get();
        }

        $activities = LearningActivity::where('active', true)
            ->with(['subject', 'gradeLevel'])
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $allAchievements = Achievement::where('active', true)->get();
        $unlockedIds = $user->achievements->pluck('id')->toArray();

        $decorations = AquariumDecoration::where('active', true)->get();
        $unlockedDecoIds = $user->fish
            ? $user->fish->decorations->pluck('decoration_id')->toArray()
            : [];

        return view('student.dashboard', [
            'user' => $user,
            'studentProfile' => $user->studentProfile,
            'fish' => $user->fish,
            'classroom' => $classroom,
            'assignments' => $assignments,
            'activities' => $activities,
            'achievements' => $allAchievements,
            'unlockedAchievementIds' => $unlockedIds,
            'decorations' => $decorations,
            'unlockedDecorationIds' => $unlockedDecoIds,
        ]);
    }

    public function aquarium()
    {
        $user = Auth::user();
        $user->load(['studentProfile', 'fish.stage', 'fish.decorations.decoration']);

        $decorations = AquariumDecoration::where('active', true)->get();
        $unlockedDecorations = $user->fish
            ? $user->fish->decorations()->with('decoration')->get()
            : collect();

        return view('student.aquarium', [
            'user' => $user,
            'fish' => $user->fish,
            'allDecorations' => $decorations,
            'unlockedDecorations' => $unlockedDecorations,
        ]);
    }

    public function updateFish(Request $request)
    {
        $user = Auth::user();
        $fish = $user->fish;

        if (!$fish) {
            return response()->json(['success' => false, 'message' => 'Fish not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:50',
            'species' => 'sometimes|string|in:clownfish,blue_tang,yellow_tang,angelfish,pufferfish',
            'color' => 'sometimes|string|max:7',
            'decorations' => 'sometimes|array',
        ]);

        if (isset($validated['name'])) $fish->name = $validated['name'];
        if (isset($validated['species'])) $fish->species = $validated['species'];
        if (isset($validated['color'])) $fish->color = $validated['color'];
        $fish->save();

        // Handle placed decorations if passed
        if (isset($validated['decorations'])) {
            foreach ($validated['decorations'] as $deco) {
                if (isset($deco['id'])) {
                    FishDecoration::where('fish_id', $fish->id)
                        ->where('decoration_id', $deco['id'])
                        ->update([
                            'position_x' => $deco['x'] ?? 0,
                            'position_y' => $deco['y'] ?? 0,
                            'placed' => $deco['placed'] ?? true,
                        ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Fish aquarium updated!',
            'fish' => $fish,
        ]);
    }

    public function showAssignment(Assignment $assignment)
    {
        $user = Auth::user();
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        return view('student.assignment-show', [
            'assignment' => $assignment->load(['subject', 'objectives', 'classroom']),
            'submission' => $submission,
        ]);
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'content' => 'required|string|min:5|max:5000',
        ]);

        $submission = Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => $user->id],
            [
                'content' => $validated['content'],
                'status' => 'submitted',
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('student.assignments.show', $assignment)
            ->with('success', '🌟 Great work! Your homework has been submitted to your teacher!');
    }
}
