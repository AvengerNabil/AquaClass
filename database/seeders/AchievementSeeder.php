<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'First Splash',
                'slug' => 'first-splash',
                'description' => 'Complete your very first learning activity in AquaClass!',
                'icon' => '🌊',
                'category' => 'milestone',
                'criteria_type' => 'activities_completed',
                'criteria_value' => 1,
                'points_bonus' => 10,
            ],
            [
                'name' => 'Math Guppy',
                'slug' => 'math-guppy',
                'description' => 'Complete 3 math challenges with top marks!',
                'icon' => '🧮',
                'category' => 'math',
                'criteria_type' => 'activities_completed',
                'criteria_value' => 3,
                'points_bonus' => 20,
            ],
            [
                'name' => 'Word Coral',
                'slug' => 'word-coral',
                'description' => 'Master English vocabulary and reading challenges.',
                'icon' => '📖',
                'category' => 'reading',
                'criteria_type' => 'activities_completed',
                'criteria_value' => 3,
                'points_bonus' => 20,
            ],
            [
                'name' => 'Junior Biologist',
                'slug' => 'junior-biologist',
                'description' => 'Explore the marine science questions and learn about ocean life.',
                'icon' => '🔬',
                'category' => 'science',
                'criteria_type' => 'activities_completed',
                'criteria_value' => 3,
                'points_bonus' => 25,
            ],
            [
                'name' => 'Century Swimmer',
                'slug' => 'century-swimmer',
                'description' => 'Reach 100 total growth points on your journey!',
                'icon' => '💯',
                'category' => 'milestone',
                'criteria_type' => 'points_total',
                'criteria_value' => 100,
                'points_bonus' => 30,
            ],
            [
                'name' => 'Golden Fin',
                'slug' => 'golden-fin',
                'description' => 'Accumulate 350 points and ascend to Coral Guardian!',
                'icon' => '⭐',
                'category' => 'milestone',
                'criteria_type' => 'points_total',
                'criteria_value' => 350,
                'points_bonus' => 50,
            ],
            [
                'name' => 'Daily Tide',
                'slug' => 'daily-tide',
                'description' => 'Maintain a 3-day active learning streak.',
                'icon' => '🔥',
                'category' => 'milestone',
                'criteria_type' => 'streak_days',
                'criteria_value' => 3,
                'points_bonus' => 15,
            ],
            [
                'name' => 'Homework Hero',
                'slug' => 'homework-hero',
                'description' => 'Submit teacher assignments on time with high effort.',
                'icon' => '📝',
                'category' => 'homework',
                'criteria_type' => 'assignments_completed',
                'criteria_value' => 2,
                'points_bonus' => 25,
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::updateOrCreate(['slug' => $ach['slug']], $ach);
        }
    }
}
