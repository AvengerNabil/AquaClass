<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityOption extends Model
{
    protected $fillable = ['question_id', 'option_text', 'option_data', 'is_correct', 'sort_order'];

    protected function casts(): array
    {
        return [
            'option_data' => 'array',
            'is_correct' => 'boolean',
        ];
    }

    public function question()
    {
        return $this->belongsTo(ActivityQuestion::class, 'question_id');
    }
}
