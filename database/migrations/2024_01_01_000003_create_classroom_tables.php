<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Classrooms
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->foreignId('framework_id')->nullable()->constrained('curriculum_frameworks')->onDelete('set null');
            $table->string('join_code', 8)->unique();
            $table->string('language', 10)->default('en');
            $table->string('timezone', 50)->default('UTC');
            $table->unsignedSmallInteger('max_students')->default(40);
            $table->text('description')->nullable();
            $table->boolean('leaderboard_enabled')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('teacher_id');
            $table->index('join_code');
        });

        // Classroom Members
        Schema::create('classroom_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['student', 'co_teacher'])->default('student');
            $table->enum('status', ['active', 'pending', 'removed'])->default('active');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->unique(['classroom_id', 'user_id']);
            $table->index('user_id');
        });

        // Classroom Curriculum Settings
        Schema::create('classroom_curriculum_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->json('enabled_objectives')->nullable(); // Array of objective IDs
            $table->json('custom_settings')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['classroom_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_curriculum_settings');
        Schema::dropIfExists('classroom_members');
        Schema::dropIfExists('classrooms');
    }
};
