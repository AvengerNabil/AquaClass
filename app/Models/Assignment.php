<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'classroom_id', 'title', 'description', 'type', 'subject_id',
        'activity_id', 'due_date', 'points', 'status', 'allow_late',
        'max_submissions', 'assigned_to', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'allow_late' => 'boolean',
            'points' => 'integer',
            'max_submissions' => 'integer',
            'assigned_to' => 'array',
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

    public function activity()
    {
        return $this->belongsTo(LearningActivity::class, 'activity_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function objectives()
    {
        return $this->belongsToMany(LearningObjective::class, 'assignment_objectives', 'assignment_id', 'objective_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast();
    }

    public function isAssignedTo(User $user): bool
    {
        if (is_null($this->assigned_to)) {
            return true; // Assigned to entire class
        }
        return in_array($user->id, $this->assigned_to);
    }
}
