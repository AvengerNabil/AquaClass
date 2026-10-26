<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Assignments
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['homework', 'activity', 'reading', 'project', 'challenge'])->default('homework');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('activity_id')->nullable()->constrained('learning_activities')->onDelete('set null');
            $table->timestamp('due_date')->nullable();
            $table->unsignedInteger('points')->default(10);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->boolean('allow_late')->default(false);
            $table->unsignedTinyInteger('max_submissions')->default(1);
            $table->json('assigned_to')->nullable(); // Null = entire class, or array of user IDs
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['classroom_id', 'status']);
            $table->index('due_date');
        });

        // Assignment-Objective Links
        Schema::create('assignment_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('objective_id')->constrained('learning_objectives')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['assignment_id', 'objective_id']);
        });

        // Submissions
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'needs_revision', 'rejected'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('feedback')->nullable();
            $table->unsignedInteger('points_awarded')->default(0);
            $table->boolean('points_credited')->default(false); // Prevents duplicate point awards
            $table->timestamps();

            $table->unique(['assignment_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('assignment_objectives');
        Schema::dropIfExists('assignments');
    }
};
