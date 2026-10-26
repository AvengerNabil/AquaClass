<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Learning Activities
        Schema::create('learning_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', [
                'multiple_choice', 'number_exercise', 'word_matching',
                'spelling', 'reading_comprehension', 'story_sequencing',
                'science_classification', 'drag_and_drop', 'short_quiz',
                'teacher_created'
            ]);
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->foreignId('objective_id')->nullable()->constrained('learning_objectives')->onDelete('set null');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->text('instructions')->nullable();
            $table->unsignedInteger('max_points')->default(10);
            $table->boolean('retry_allowed')->default(true);
            $table->unsignedTinyInteger('max_attempts')->default(3);
            $table->unsignedInteger('time_limit_seconds')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_system')->default(false); // System-seeded vs teacher-created
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['subject_id', 'grade_level_id']);
            $table->index('type');
        });

        // Activity Questions
        Schema::create('activity_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('learning_activities')->onDelete('cascade');
            $table->enum('type', [
                'multiple_choice', 'true_false', 'fill_blank', 'matching',
                'ordering', 'number_input', 'short_answer', 'drag_drop'
            ]);
            $table->text('question_text');
            $table->json('question_data')->nullable(); // Images, audio, extra context
            $table->text('correct_answer');
            $table->text('explanation')->nullable();
            $table->unsignedInteger('points')->default(1);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('activity_id');
        });

        // Activity Options (for MCQ, matching, etc.)
        Schema::create('activity_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('activity_questions')->onDelete('cascade');
            $table->string('option_text');
            $table->json('option_data')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('question_id');
        });

        // Activity Attempts (student answers)
        Schema::create('activity_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->constrained('learning_activities')->onDelete('cascade');
            $table->foreignId('question_id')->nullable()->constrained('activity_questions')->onDelete('cascade');
            $table->json('answer_data');
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('points_earned')->default(0);
            $table->unsignedTinyInteger('attempt_number')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'activity_id']);
            $table->index(['user_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_attempts');
        Schema::dropIfExists('activity_options');
        Schema::dropIfExists('activity_questions');
        Schema::dropIfExists('learning_activities');
    }
};
