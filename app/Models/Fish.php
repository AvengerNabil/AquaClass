<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
    protected $table = 'fish';

    protected $fillable = [
        'user_id', 'name', 'species', 'color', 'size',
        'stage_id', 'total_growth_points', 'customizations',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'decimal:2',
            'total_growth_points' => 'integer',
            'customizations' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stage()
    {
        return $this->belongsTo(FishStage::class, 'stage_id');
    }

    public function decorations()
    {
        return $this->hasMany(FishDecoration::class);
    }

    public function unlockedDecorations()
    {
        return $this->belongsToMany(AquariumDecoration::class, 'fish_decorations', 'fish_id', 'decoration_id')
            ->withPivot('position_x', 'position_y', 'placed', 'unlocked_at');
    }

    /**
     * Update fish growth based on total points.
     */
    public function updateGrowth(int $totalPoints): void
    {
        $this->total_growth_points = $totalPoints;

        $newStage = FishStage::where('min_points', '<=', $totalPoints)
            ->orderBy('min_points', 'desc')
            ->first();

        if ($newStage && $newStage->id !== $this->stage_id) {
            $this->stage_id = $newStage->id;
            $this->size = $newStage->size_multiplier;
        }

        $this->save();
    }

    public function getGrowthPercentage(): float
    {
        $stage = $this->stage;
        if (!$stage || $stage->max_points <= $stage->min_points) {
            return 100;
        }
        $progress = $this->total_growth_points - $stage->min_points;
        $range = $stage->max_points - $stage->min_points;
        return min(100, round(($progress / $range) * 100, 1));
    }

    public function getNextStageName(): ?string
    {
        $nextStage = FishStage::where('min_points', '>', $this->total_growth_points)
            ->orderBy('min_points')
            ->first();
        return $nextStage?->display_name;
    }

    public function getPointsToNextStage(): int
    {
        $nextStage = FishStage::where('min_points', '>', $this->total_growth_points)
            ->orderBy('min_points')
            ->first();
        return $nextStage ? max(0, $nextStage->min_points - $this->total_growth_points) : 0;
    }
}
