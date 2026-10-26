<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'teacher_id', 'grade_level_id', 'framework_id',
        'join_code', 'language', 'timezone', 'max_students',
        'description', 'leaderboard_enabled', 'active',
    ];

    protected function casts(): array
    {
        return [
            'leaderboard_enabled' => 'boolean',
            'active' => 'boolean',
            'max_students' => 'integer',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function framework()
    {
        return $this->belongsTo(CurriculumFramework::class, 'framework_id');
    }

    public function members()
    {
        return $this->hasMany(ClassroomMember::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'classroom_members')
            ->withPivot('role', 'status', 'joined_at')
            ->wherePivot('role', 'student')
            ->wherePivot('status', 'active');
    }

    public function activeMembers()
    {
        return $this->members()->where('status', 'active');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function curriculumSettings()
    {
        return $this->hasMany(ClassroomCurriculumSetting::class);
    }

    public function aquarium()
    {
        return $this->hasOne(ClassroomAquarium::class);
    }

    public function lessonPlans()
    {
        return $this->hasMany(LessonPlan::class);
    }

    public static function generateJoinCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (static::where('join_code', $code)->exists());
        return $code;
    }

    public function isFull(): bool
    {
        return $this->activeMembers()->count() >= $this->max_students;
    }
}
