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
        Schema::create('group_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();          // CrossFit, Yoga, Zumba, HIIT, Boxing, etc.
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->unsignedSmallInteger('capacity')->default(20);
            // Schedule
            $table->string('schedule_days')->nullable();     // JSON array: ["Mon","Wed","Fri"]
            $table->time('start_time')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            // Trainer
            $table->foreignId('trainer_id')->nullable()->constrained('users')->nullOnDelete();
            // Location
            $table->string('location')->nullable();          // e.g. "Studio A", "Outdoor Court"
            $table->string('status', 20)->default('active'); // active, inactive, cancelled
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('group_class_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_class_id')->constrained('group_classes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // member
            $table->string('status', 20)->default('booked');  // booked, attended, cancelled
            $table->timestamp('booked_at')->useCurrent();
            $table->timestamps();

            $table->unique(['group_class_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_class_bookings');
        Schema::dropIfExists('group_classes');
    }
};
