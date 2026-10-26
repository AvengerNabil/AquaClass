<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLearningProgress extends Model
{
    protected $table = 'student_learning_progress';

    protected $fillable = [
        'user_id', 'objective_id', 'status', 'proficiency_score',
        'attempts_count', 'last_attempt_at', 'mastered_at',
    ];

    protected function casts(): array
    {
        return [
            'proficiency_score' => 'integer',
            'attempts_count' => 'integer',
            'last_attempt_at' => 'datetime',
            'mastered_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function objective()
    {
        return $this->belongsTo(LearningObjective::class, 'objective_id');
    }
}
