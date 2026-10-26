<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    protected $fillable = [
        'classroom_id', 'subject_id', 'title', 'description',
        'planned_date', 'objective_ids', 'activity_ids', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'planned_date' => 'date',
            'objective_ids' => 'array',
            'activity_ids' => 'array',
        ];
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
