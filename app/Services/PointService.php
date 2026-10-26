<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointTransaction;
use App\Models\Fish;
use App\Models\FishStage;
use App\Models\Achievement;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\DB;

class PointService
{
    /**
     * Award points to a student with ledger logging and fish growth update.
     */
    public function awardPoints(
        User $user,
        int $points,
        string $type,
        ?string $sourceType = null,
        ?int $sourceId = null,
        ?string $description = null,
        ?int $awardedBy = null
    ): array {
        if ($points <= 0) {
            return ['success' => false, 'message' => 'Points must be positive.'];
        }

        return DB::transaction(function () use ($user, $points, $type, $sourceType, $sourceId, $description, $awardedBy) {
            // 1. Record immutable ledger transaction
            $transaction = PointTransaction::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => $type,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'description' => $description,
                'awarded_by' => $awardedBy,
            ]);

            // 2. Update Student Profile
            $profile = $user->studentProfile;
            if (!$profile) {
                $profile = StudentProfile::create([
                    'user_id' => $user->id,
                    'display_name' => $user->name,
                    'total_points' => 0,
                    'current_streak' => 1,
                    'last_activity_date' => now()->toDateString(),
                ]);
            }

            $profile->total_points += $points;

            // Check streak
            $today = now()->toDateString();
            $yesterday = now()->subDay()->toDateString();
            if ($profile->last_activity_date === $yesterday) {
                $profile->current_streak += 1;
            } elseif ($profile->last_activity_date !== $today) {
                $profile->current_streak = 1;
            }
            $profile->last_activity_date = $today;
            $profile->save();

            // 3. Update Fish Growth
            $fish = $user->fish;
            $stageUpgraded = false;
            $oldStageName = null;
            $newStageName = null;

            if ($fish) {
                $oldStage = $fish->stage;
                $oldStageName = $oldStage?->display_name;

                $fish->updateGrowth($profile->total_points);
                $fish->refresh();

                if ($oldStage && $fish->stage_id !== $oldStage->id) {
                    $stageUpgraded = true;
                    $newStageName = $fish->stage?->display_name;
                }
            }

            // 4. Check & Unlock Achievements
            $unlockedAchievements = $this->checkAchievements($user, $profile->total_points, $profile->current_streak);

            return [
                'success' => true,
                'points_awarded' => $points,
                'total_points' => $profile->total_points,
                'current_streak' => $profile->current_streak,
                'stage_upgraded' => $stageUpgraded,
                'old_stage' => $oldStageName,
                'new_stage' => $newStageName,
                'unlocked_achievements' => $unlockedAchievements,
            ];
        });
    }

    /**
     * Check criteria for unlocking achievements
     */
    protected function checkAchievements(User $user, int $totalPoints, int $streak): array
    {
        $newlyUnlocked = [];
        $earnedIds = $user->achievements()->pluck('achievements.id')->toArray();

        $achievements = Achievement::where('active', true)
            ->whereNotIn('id', $earnedIds)
            ->get();

        foreach ($achievements as $ach) {
            $unlocked = false;

            if ($ach->criteria_type === 'points_total' && $totalPoints >= $ach->criteria_value) {
                $unlocked = true;
            } elseif ($ach->criteria_type === 'streak_days' && $streak >= $ach->criteria_value) {
                $unlocked = true;
            } elseif ($ach->criteria_type === 'activities_completed') {
                $count = $user->activityAttempts()->where('is_correct', true)->distinct('activity_id')->count('activity_id');
                if ($count >= $ach->criteria_value) {
                    $unlocked = true;
                }
            } elseif ($ach->criteria_type === 'assignments_completed') {
                $count = $user->submissions()->where('status', 'approved')->count();
                if ($count >= $ach->criteria_value) {
                    $unlocked = true;
                }
            }

            if ($unlocked) {
                $user->achievements()->attach($ach->id, ['earned_at' => now()]);
                $newlyUnlocked[] = $ach;

                // Award achievement bonus if defined
                if ($ach->points_bonus > 0) {
                    PointTransaction::create([
                        'user_id' => $user->id,
                        'points' => $ach->points_bonus,
                        'type' => 'achievement_bonus',
                        'source_type' => 'Achievement',
                        'source_id' => $ach->id,
                        'description' => 'Bonus for unlocking ' . $ach->name,
                    ]);
                    $user->studentProfile()->increment('total_points', $ach->points_bonus);
                }
            }
        }

        return $newlyUnlocked;
    }
}
