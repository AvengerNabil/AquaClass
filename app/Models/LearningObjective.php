<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningObjective extends Model
{
    use HasFactory;

    protected $fillable = [
        'framework_id', 'grade_level_id', 'subject_id', 'standard_id',
        'topic', 'description', 'difficulty', 'min_age', 'max_age',
        'prerequisites', 'language', 'source', 'active',
    ];

    protected function casts(): array
    {
        return [
            'prerequisites' => 'array',
            'active' => 'boolean',
        ];
    }

    public function framework()
    {
        return $this->belongsTo(CurriculumFramework::class, 'framework_id');
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function standard()
    {
        return $this->belongsTo(CurriculumStandard::class);
    }

    public function activities()
    {
        return $this->hasMany(LearningActivity::class, 'objective_id');
    }

    public function assignments()
    {
        return $this->belongsToMany(Assignment::class, 'assignment_objectives', 'objective_id', 'assignment_id');
    }

    public function studentProgress()
    {
        return $this->hasMany(StudentLearningProgress::class, 'objective_id');
    }
}
