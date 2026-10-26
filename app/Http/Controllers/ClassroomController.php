<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassroomMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    public function showJoin()
    {
        return view('classroom.join');
    }

    public function processJoin(Request $request)
    {
        $validated = $request->validate([
            'join_code' => 'required|string|size:7|alpha_num',
        ]);

        $code = strtoupper(trim($validated['join_code']));
        $classroom = Classroom::where('join_code', $code)->where('active', true)->first();

        if (!$classroom) {
            return back()->withErrors(['join_code' => 'Invalid or inactive classroom code. Check with your teacher!'])->withInput();
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('info', 'Please log in to join your classroom.');
        }

        if ($classroom->isFull()) {
            return back()->withErrors(['join_code' => 'This classroom is currently full.']);
        }

        ClassroomMember::firstOrCreate(
            ['classroom_id' => $classroom->id, 'user_id' => $user->id],
            ['role' => 'student', 'status' => 'active', 'joined_at' => now()]
        );

        return redirect()->route('student.dashboard')->with('success', "🎉 Welcome to {$classroom->name}!");
    }
}
