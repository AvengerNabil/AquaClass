<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'google_id', 'avatar', 'role',
        'onboarded', 'locale', 'timezone', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token', 'google_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'onboarded' => 'boolean',
        ];
    }

    // Role checks
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relationships
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function fish()
    {
        return $this->hasOne(Fish::class);
    }

    public function classroomsAsTeacher()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function classroomMemberships()
    {
        return $this->hasMany(ClassroomMember::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_members')
            ->withPivot('role', 'status', 'joined_at')
            ->wherePivot('status', 'active');
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function activityAttempts()
    {
        return $this->hasMany(ActivityAttempt::class);
    }

    public function weeklyGoals()
    {
        return $this->hasMany(WeeklyGoal::class);
    }

    public function learningProgress()
    {
        return $this->hasMany(StudentLearningProgress::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // Helpers
    public function getTotalPoints(): int
    {
        return $this->studentProfile?->total_points ?? 0;
    }

    public function getDisplayName(): string
    {
        return $this->studentProfile?->display_name ?? $this->name;
    }
}
