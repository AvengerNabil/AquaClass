<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityAttempt extends Model
{
    protected $fillable = [
        'user_id', 'activity_id', 'quiz_session_id', 'question_id', 'answer_data',
        'is_correct', 'points_earned', 'attempt_number', 'started_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'answer_data' => 'array',
            'is_correct' => 'boolean',
            'points_earned' => 'integer',
            'started_at' => 'datetime',
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

    public function quizSession()
    {
        return $this->belongsTo(QuizSession::class, 'quiz_session_id');
    }

    public function question()
    {
        return $this->belongsTo(ActivityQuestion::class, 'question_id');
    }

}
