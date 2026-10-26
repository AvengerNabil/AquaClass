<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'points', 'type', 'source_type', 'source_id',
        'description', 'awarded_by',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function awarder()
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }
}
