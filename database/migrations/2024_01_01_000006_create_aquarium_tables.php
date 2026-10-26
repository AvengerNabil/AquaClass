<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fish Stages
        Schema::create('fish_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // tiny_fry, little_swimmer, etc.
            $table->string('display_name'); // Tiny Fry, Little Swimmer, etc.
            $table->unsignedInteger('min_points')->default(0);
            $table->unsignedInteger('max_points')->default(0);
            $table->decimal('size_multiplier', 3, 2)->default(1.00);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('name');
        });

        // Fish
        Schema::create('fish', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('My Fish');
            $table->string('species')->default('clownfish');
            $table->string('color', 7)->default('#FF6B35');
            $table->decimal('size', 5, 2)->default(1.00);
            $table->foreignId('stage_id')->constrained('fish_stages')->onDelete('restrict');
            $table->unsignedBigInteger('total_growth_points')->default(0);
            $table->json('customizations')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });

        // Point Transactions (append-only ledger)
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points'); // Can be negative for corrections
            $table->enum('type', [
                'homework_completion', 'activity_completion', 'reading_milestone',
                'extra_practice', 'achievement_bonus', 'challenge_reward',
                'teacher_award', 'correction'
            ]);
            $table->string('source_type')->nullable(); // Assignment, Activity, etc.
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('awarded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['source_type', 'source_id']);
        });

        // Aquarium Decorations
        Schema::create('aquarium_decorations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['plant', 'rock', 'coral', 'shell', 'treasure', 'castle', 'background']);
            $table->string('asset_key'); // Reference to Three.js asset
            $table->unsignedInteger('unlock_points')->default(0);
            $table->foreignId('unlock_achievement_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Fish Decorations (unlocked/placed by student)
        Schema::create('fish_decorations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fish_id')->constrained()->onDelete('cascade');
            $table->foreignId('decoration_id')->constrained('aquarium_decorations')->onDelete('cascade');
            $table->decimal('position_x', 5, 2)->default(0);
            $table->decimal('position_y', 5, 2)->default(0);
            $table->boolean('placed')->default(false);
            $table->timestamp('unlocked_at')->useCurrent();
            $table->timestamps();

            $table->unique(['fish_id', 'decoration_id']);
        });

        // Classroom Aquariums
        Schema::create('classroom_aquariums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->string('theme')->default('ocean');
            $table->string('background')->default('deep_blue');
            $table->json('decorations_data')->nullable();
            $table->timestamps();

            $table->unique('classroom_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_aquariums');
        Schema::dropIfExists('fish_decorations');
        Schema::dropIfExists('aquarium_decorations');
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('fish');
        Schema::dropIfExists('fish_stages');
    }
};
