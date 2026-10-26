<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyGoal extends Model
{
    protected $fillable = [
        'user_id', 'classroom_id', 'week_start',
        'target_points', 'earned_points', 'completed',
    ];

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'target_points' => 'integer',
            'earned_points' => 'integer',
            'completed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
