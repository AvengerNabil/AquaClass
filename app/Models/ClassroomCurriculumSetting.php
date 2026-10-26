<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomCurriculumSetting extends Model
{
    protected $fillable = ['classroom_id', 'subject_id', 'enabled_objectives', 'custom_settings', 'enabled'];

    protected function casts(): array
    {
        return [
            'enabled_objectives' => 'array',
            'custom_settings' => 'array',
            'enabled' => 'boolean',
        ];
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
