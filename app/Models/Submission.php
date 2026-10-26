<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id', 'user_id', 'content', 'file_path', 'file_name',
        'status', 'submitted_at', 'reviewed_at', 'reviewer_id',
        'feedback', 'points_awarded', 'points_credited',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'points_awarded' => 'integer',
            'points_credited' => 'boolean',
        ];
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function canAwardPoints(): bool
    {
        return $this->isApproved() && !$this->points_credited;
    }
}
