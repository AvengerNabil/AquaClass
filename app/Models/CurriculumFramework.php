<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumFramework extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'country_code', 'language', 'version', 'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function standards()
    {
        return $this->hasMany(CurriculumStandard::class, 'framework_id');
    }

    public function learningObjectives()
    {
        return $this->hasMany(LearningObjective::class, 'framework_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'framework_id');
    }
}
