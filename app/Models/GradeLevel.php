<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'min_age', 'max_age', 'sort_order'];

    public function learningObjectives()
    {
        return $this->hasMany(LearningObjective::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function activities()
    {
        return $this->hasMany(LearningActivity::class);
    }
}
