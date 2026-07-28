<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('title', 255);
            $table->text('message');

            // success, warning, error, information
            $table->string('type', 30)->default('information');
            // Related domain/module (Member, Trainer, Authentication, etc.)
            $table->string('module', 100)->index();

            // For future routing/navigation/correlation across modules/channels.
            $table->string('reference_id', 100)->nullable()->index();
            $table->string('reference_type', 100)->nullable()->index();

            // Channels are future-ready (currently: in_app).
            $table->string('channel', 30)->default('in_app')->index();

            $table->timestamp('read_at')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};

