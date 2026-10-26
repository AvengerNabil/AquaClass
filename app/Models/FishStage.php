<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishStage extends Model
{
    protected $fillable = [
        'name', 'display_name', 'min_points', 'max_points',
        'size_multiplier', 'description', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'min_points' => 'integer',
            'max_points' => 'integer',
            'size_multiplier' => 'decimal:2',
        ];
    }

    public function fish()
    {
        return $this->hasMany(Fish::class, 'stage_id');
    }
}
