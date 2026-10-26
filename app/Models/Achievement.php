<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'category',
        'criteria_type', 'criteria_value', 'points_bonus', 'active',
    ];

    protected function casts(): array
    {
        return [
            'criteria_value' => 'integer',
            'points_bonus' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}
