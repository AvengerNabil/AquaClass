<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Curriculum Frameworks (e.g., International Primary, Cambridge Primary)
        Schema::create('curriculum_frameworks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('language', 10)->default('en');
            $table->string('version', 20)->default('1.0');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['name', 'version']);
        });

        // Grade Levels
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // grade_1, grade_2, grade_3
            $table->string('display_name'); // Grade 1
            $table->unsignedTinyInteger('min_age')->default(6);
            $table->unsignedTinyInteger('max_age')->default(7);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique('name');
        });

        // Subjects
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('color', 7)->default('#3B82F6');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Curriculum Standards
        Schema::create('curriculum_standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->constrained('curriculum_frameworks')->onDelete('cascade');
            $table->string('code', 50);
            $table->text('description');
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['framework_id', 'code']);
        });

        // Learning Objectives
        Schema::create('learning_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->constrained('curriculum_frameworks')->onDelete('cascade');
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('standard_id')->nullable()->constrained('curriculum_standards')->onDelete('set null');
            $table->string('topic');
            $table->text('description');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->unsignedTinyInteger('min_age')->nullable();
            $table->unsignedTinyInteger('max_age')->nullable();
            $table->json('prerequisites')->nullable(); // Array of objective IDs
            $table->string('language', 10)->default('en');
            $table->string('source')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['framework_id', 'grade_level_id', 'subject_id']);
            $table->index('topic');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_objectives');
        Schema::dropIfExists('curriculum_standards');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('grade_levels');
        Schema::dropIfExists('curriculum_frameworks');
    }
};
