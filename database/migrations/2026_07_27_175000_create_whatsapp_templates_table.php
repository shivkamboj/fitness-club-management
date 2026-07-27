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
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('event_key'); // welcome, renewal_reminder, payment_reminder, birthday_wishes, offer_notifications, workout_reminder
            $table->string('event_title');
            $table->boolean('is_enabled')->default(true);
            $table->text('message_template');
            $table->timestamps();

            $table->unique(['gym_owner_id', 'event_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};
