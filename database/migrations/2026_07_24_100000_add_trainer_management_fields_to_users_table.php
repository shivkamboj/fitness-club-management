<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable()->after('name');
            $table->string('last_name', 100)->nullable()->after('first_name');
            $table->foreignId('gym_owner_id')->nullable()->after('role')->constrained('users')->nullOnDelete();
            $table->string('gender', 20)->nullable()->after('phone');
            $table->date('dob')->nullable()->after('gender');
            $table->date('joining_date')->nullable()->after('dob');
            $table->string('specialization')->nullable()->after('joining_date');
            $table->unsignedTinyInteger('experience')->nullable()->after('specialization');
            $table->text('certifications')->nullable()->after('experience');
            $table->text('skills')->nullable()->after('certifications');
            $table->string('profile_image')->nullable()->after('skills');
            $table->string('background_image')->nullable()->after('profile_image');
            $table->string('status', 20)->default('active')->after('background_image');
            $table->softDeletes();

            $table->index(['role', 'gym_owner_id', 'status']);
            $table->index(['gym_owner_id', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'gym_owner_id', 'status']);
            $table->dropIndex(['gym_owner_id', 'deleted_at']);
            $table->dropConstrainedForeignId('gym_owner_id');
            $table->dropColumn([
                'first_name',
                'last_name',
                'gender',
                'dob',
                'joining_date',
                'specialization',
                'experience',
                'certifications',
                'skills',
                'profile_image',
                'background_image',
                'status',
                'deleted_at',
            ]);
        });
    }
};
