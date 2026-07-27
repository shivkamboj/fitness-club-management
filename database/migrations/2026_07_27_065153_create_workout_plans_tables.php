<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workout_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('goal')->nullable(); // Hypertrophy, Fat Loss, Strength, Cardio, Endurance, etc.
            $table->unsignedTinyInteger('days_per_week')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('workout_plan_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_plan_id')->constrained('workout_plans')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_number'); // e.g. 1 to 7
            $table->string('exercise_name');
            $table->unsignedTinyInteger('sets');
            $table->string('reps'); // can be range e.g. "10-12" or "AMRAP"
            $table->string('rest')->nullable(); // e.g. "60s", "90s"
            $table->string('video_link')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('workout_plan_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // member
            $table->foreignId('workout_plan_id')->constrained('workout_plans')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete(); // trainer or owner
            $table->timestamp('assigned_at')->useCurrent();
            $table->string('status', 20)->default('active'); // active, completed, inactive
            $table->timestamps();
        });

        Schema::create('completed_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('workout_plan_exercise_id')->constrained('workout_plan_exercises')->cascadeOnDelete();
            $table->date('completed_at');
            $table->timestamps();

            $table->unique(['user_id', 'workout_plan_exercise_id', 'completed_at'], 'user_exercise_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_exercises');
        Schema::dropIfExists('workout_plan_assignments');
        Schema::dropIfExists('workout_plan_exercises');
        Schema::dropIfExists('workout_plans');
    }
};
