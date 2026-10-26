<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ClassroomController;
use App\Models\FishStage;
use App\Models\LearningActivity;
use App\Models\CurriculumFramework;

// Public Landing Page
Route::get('/', function () {
    $stages = FishStage::orderBy('sort_order')->get();
    $activities = LearningActivity::with('subject')->take(3)->get();
    return view('welcome', [
        'stages' => $stages,
        'activities' => $activities,
    ]);
})->name('home');

// Quick Demo Persona Switcher (Convenient for Pair Programming & Testing)
Route::get('/demo/{persona}', [AuthController::class, 'switchDemo'])->name('demo.switch');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public Curriculum Explorer
Route::get('/curriculum', [CurriculumController::class, 'index'])->name('curriculum.index');

// Learning Activities Player (accessible to students / all logged-in users)
Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
Route::post('/activities/{activity}/submit', [ActivityController::class, 'submit'])->name('activities.submit');
Route::post('/api/activities/{activity}/submit', [ActivityController::class, 'submit']);

// Classroom Join Code Flow
Route::get('/join', [ClassroomController::class, 'showJoin'])->name('classroom.join');
Route::post('/join', [ClassroomController::class, 'processJoin'])->middleware('auth');

// Student Protected Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/aquarium', [StudentDashboardController::class, 'aquarium'])->name('aquarium');
    Route::post('/fish/update', [StudentDashboardController::class, 'updateFish'])->name('fish.update');
    Route::get('/assignments/{assignment}', [StudentDashboardController::class, 'showAssignment'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [StudentDashboardController::class, 'submitAssignment'])->name('assignments.submit');
});

// Teacher Protected Routes
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/classrooms/{classroom}/tank', [TeacherDashboardController::class, 'classroomTank'])->name('classrooms.tank');
    Route::get('/classrooms/{classroom}/assignments', [TeacherDashboardController::class, 'assignments'])->name('classrooms.assignments');
    Route::get('/classrooms/{classroom}/assignments/create', [TeacherDashboardController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/classrooms/{classroom}/assignments', [TeacherDashboardController::class, 'storeAssignment'])->name('assignments.store');
    Route::post('/submissions/{submission}/review', [TeacherDashboardController::class, 'reviewSubmission'])->name('submissions.review');
    Route::post('/points/award', [TeacherDashboardController::class, 'awardCustomPoints'])->name('points.award');
    Route::get('/classrooms/{classroom}/export', [TeacherDashboardController::class, 'exportClassroomCsv'])->name('classrooms.export');
});
