<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'type', 'subject_id', 'grade_level_id', 'objective_id',
        'difficulty', 'instructions', 'max_points', 'retry_allowed',
        'max_attempts', 'time_limit_seconds', 'created_by', 'is_system', 'active',
    ];

    protected function casts(): array
    {
        return [
            'retry_allowed' => 'boolean',
            'is_system' => 'boolean',
            'active' => 'boolean',
            'max_points' => 'integer',
            'max_attempts' => 'integer',
        ];
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function objective()
    {
        return $this->belongsTo(LearningObjective::class, 'objective_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(ActivityQuestion::class, 'activity_id')->orderBy('sort_order');
    }

    public function attempts()
    {
        return $this->hasMany(ActivityAttempt::class, 'activity_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'activity_id');
    }

    public function quizSessions()
    {
        return $this->hasMany(QuizSession::class, 'activity_id');
    }
}

