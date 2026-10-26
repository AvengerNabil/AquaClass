<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CurriculumSeeder::class,
            AquariumSeeder::class,
            AchievementSeeder::class,
            AquaClassDemoSeeder::class,
            QuestionBankSeeder::class,
        ]);
    }
}

