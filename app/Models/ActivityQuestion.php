<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityQuestion extends Model
{
    protected $fillable = [
        'activity_id', 'type', 'question_text', 'question_data',
        'correct_answer', 'explanation', 'points', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'question_data' => 'array',
            'points' => 'integer',
        ];
    }

    public function activity()
    {
        return $this->belongsTo(LearningActivity::class, 'activity_id');
    }

    public function options()
    {
        return $this->hasMany(ActivityOption::class, 'question_id')->orderBy('sort_order');
    }

    public function attempts()
    {
        return $this->hasMany(ActivityAttempt::class, 'question_id');
    }
}
