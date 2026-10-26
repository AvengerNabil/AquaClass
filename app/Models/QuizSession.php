<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_token',
        'activity_id',
        'session_length',
        'question_ids',
        'answers',
        'earned_points',
        'correct_count',
        'total_questions',
        'completed',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'question_ids' => 'array',
            'answers' => 'array',
            'session_length' => 'integer',
            'earned_points' => 'integer',
            'correct_count' => 'integer',
            'total_questions' => 'integer',
            'completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(LearningActivity::class, 'activity_id');
    }

    public function attempts()
    {
        return $this->hasMany(ActivityAttempt::class, 'quiz_session_id');
    }
}
