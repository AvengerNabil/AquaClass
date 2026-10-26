<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('display_name');
            $table->foreignId('grade_level_id')->nullable()->constrained('grade_levels')->onDelete('set null');
            $table->unsignedBigInteger('total_points')->default(0);
            $table->unsignedInteger('current_streak')->default(0);
            $table->date('last_activity_date')->nullable();
            $table->string('parent_email')->nullable();
            $table->json('preferences')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
