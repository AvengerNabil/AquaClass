<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'display_name', 'grade_level_id', 'total_points',
        'current_streak', 'last_activity_date', 'parent_email', 'preferences',
    ];

    protected function casts(): array
    {
        return [
            'total_points' => 'integer',
            'current_streak' => 'integer',
            'last_activity_date' => 'date',
            'preferences' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }
}
