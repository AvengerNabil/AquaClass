<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumStandard extends Model
{
    use HasFactory;

    protected $fillable = ['framework_id', 'code', 'description', 'grade_level_id', 'subject_id'];

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

    public function objectives()
    {
        return $this->hasMany(LearningObjective::class, 'standard_id');
    }
}
