<?php

namespace App\Http\Controllers;

use App\Models\CurriculumFramework;
use App\Models\GradeLevel;
use App\Models\Subject;
use App\Models\LearningObjective;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index(Request $request)
    {
        $gradeLevels = GradeLevel::orderBy('sort_order')->get();
        $subjects = Subject::orderBy('sort_order')->get();

        $selectedGrade = $request->get('grade', $gradeLevels->first()?->name ?? 'grade_1');
        $selectedSubject = $request->get('subject', 'all');

        $query = LearningObjective::with(['gradeLevel', 'subject', 'framework']);

        if ($selectedGrade !== 'all') {
            $query->whereHas('gradeLevel', fn($q) => $q->where('name', $selectedGrade));
        }

        if ($selectedSubject !== 'all') {
            $query->whereHas('subject', fn($q) => $q->where('slug', $selectedSubject));
        }

        $objectives = $query->paginate(12)->withQueryString();

        return view('curriculum.index', [
            'gradeLevels' => $gradeLevels,
            'subjects' => $subjects,
            'selectedGrade' => $selectedGrade,
            'selectedSubject' => $selectedSubject,
            'objectives' => $objectives,
        ]);
    }
}
