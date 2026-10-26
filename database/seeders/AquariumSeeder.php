<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FishStage;
use App\Models\AquariumDecoration;

class AquariumSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fish Stages
        $stages = [
            [
                'name' => 'tiny_fry',
                'display_name' => 'Tiny Fry 🐣',
                'min_points' => 0,
                'max_points' => 49,
                'size_multiplier' => 0.75,
                'description' => 'Just hatched! A curious little fry exploring the quiet shallows.',
                'sort_order' => 1,
            ],
            [
                'name' => 'little_swimmer',
                'display_name' => 'Little Guppy 🐟',
                'min_points' => 50,
                'max_points' => 149,
                'size_multiplier' => 1.00,
                'description' => 'Swims with confidence! Loving homework, stories, and discovery.',
                'sort_order' => 2,
            ],
            [
                'name' => 'reef_explorer',
                'display_name' => 'Reef Explorer 🐠',
                'min_points' => 150,
                'max_points' => 349,
                'size_multiplier' => 1.35,
                'description' => 'Vibrant fins and glowing scales! Discovers hidden coral secrets.',
                'sort_order' => 3,
            ],
            [
                'name' => 'coral_guardian',
                'display_name' => 'Coral Guardian 🐡',
                'min_points' => 350,
                'max_points' => 699,
                'size_multiplier' => 1.70,
                'description' => 'A respected leader in the underwater classroom, protecting classmates.',
                'sort_order' => 4,
            ],
            [
                'name' => 'ocean_champion',
                'display_name' => 'Ocean Champion 🐬',
                'min_points' => 700,
                'max_points' => 99999,
                'size_multiplier' => 2.20,
                'description' => 'Legendary status! Swims swiftly across the deep seas with radiant colors.',
                'sort_order' => 5,
            ],
        ];

        foreach ($stages as $stage) {
            FishStage::updateOrCreate(['name' => $stage['name']], $stage);
        }

        // 2. Aquarium Decorations
        $decorations = [
            [
                'name' => 'Emerald Kelp',
                'type' => 'plant',
                'asset_key' => 'kelp_emerald',
                'unlock_points' => 25,
                'description' => 'Gentle swaying sea plants that create cozy hiding spots.',
            ],
            [
                'name' => 'Purple Coral Fan',
                'type' => 'coral',
                'asset_key' => 'coral_fan_purple',
                'unlock_points' => 60,
                'description' => 'A vibrant purple fan coral blooming with oceanic energy.',
            ],
            [
                'name' => 'Glow Anemone',
                'type' => 'plant',
                'asset_key' => 'anemone_glow',
                'unlock_points' => 100,
                'description' => 'Bioluminescent tentacles that sparkle under gentle waves.',
            ],
            [
                'name' => 'Pirate Treasure Chest',
                'type' => 'treasure',
                'asset_key' => 'treasure_chest',
                'unlock_points' => 175,
                'description' => 'An ancient chest filled with sparkling pearls and shiny coins.',
            ],
            [
                'name' => 'Sea Pearl Oyster',
                'type' => 'shell',
                'asset_key' => 'oyster_pearl',
                'unlock_points' => 250,
                'description' => 'An open giant seashell displaying a radiant luminescent pearl.',
            ],
            [
                'name' => 'Sunken Atlantis Castle',
                'type' => 'castle',
                'asset_key' => 'atlantis_castle',
                'unlock_points' => 400,
                'description' => 'A grand underwater palace tower where regal fish gather.',
            ],
            [
                'name' => 'Volcanic Basalt Arch',
                'type' => 'rock',
                'asset_key' => 'volcanic_arch',
                'unlock_points' => 550,
                'description' => 'A majestic dark volcanic rock tunnel sculpted by currents.',
            ],
        ];

        foreach ($decorations as $dec) {
            AquariumDecoration::updateOrCreate(['name' => $dec['name']], $dec);
        }
    }
}
