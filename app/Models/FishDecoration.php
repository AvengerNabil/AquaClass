<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishDecoration extends Model
{
    protected $fillable = [
        'fish_id', 'decoration_id', 'position_x', 'position_y', 'placed', 'unlocked_at',
    ];

    protected function casts(): array
    {
        return [
            'placed' => 'boolean',
            'unlocked_at' => 'datetime',
        ];
    }

    public function fish()
    {
        return $this->belongsTo(Fish::class);
    }

    public function decoration()
    {
        return $this->belongsTo(AquariumDecoration::class, 'decoration_id');
    }
}
