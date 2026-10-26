<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_token', 64)->unique();
            $table->foreignId('activity_id')->constrained('learning_activities')->cascadeOnDelete();
            $table->unsignedTinyInteger('session_length')->default(10);
            $table->json('question_ids');
            $table->json('answers')->nullable();
            $table->unsignedInteger('earned_points')->default(0);
            $table->unsignedTinyInteger('correct_count')->default(0);
            $table->unsignedTinyInteger('total_questions')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'activity_id']);
            $table->index('session_token');
        });

        Schema::table('activity_attempts', function (Blueprint $table) {
            $table->foreignId('quiz_session_id')->nullable()->after('activity_id')->constrained('quiz_sessions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activity_attempts', function (Blueprint $table) {
            $table->dropForeign(['quiz_session_id']);
            $table->dropColumn('quiz_session_id');
        });

        Schema::dropIfExists('quiz_sessions');
    }
};
