<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'color', 'sort_order'];

    public function learningObjectives()
    {
        return $this->hasMany(LearningObjective::class);
    }

    public function activities()
    {
        return $this->hasMany(LearningActivity::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
