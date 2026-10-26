<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomAquarium extends Model
{
    protected $table = 'classroom_aquariums';

    protected $fillable = ['classroom_id', 'theme', 'background', 'decorations_data'];

    protected function casts(): array
    {
        return ['decorations_data' => 'array'];
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
