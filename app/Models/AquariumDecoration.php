<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AquariumDecoration extends Model
{
    protected $fillable = [
        'name', 'type', 'asset_key', 'unlock_points',
        'unlock_achievement_id', 'description', 'active',
    ];

    protected function casts(): array
    {
        return [
            'unlock_points' => 'integer',
            'active' => 'boolean',
        ];
    }
}
