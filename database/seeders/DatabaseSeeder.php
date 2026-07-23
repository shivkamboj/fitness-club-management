<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'gymmember@example.com'],
            [
                'name' => 'Gym Member',
                'phone' => '9999999990',
                'gym_name' => null,
                'role' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make('General123..'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'phone' => '9999999991',
                'gym_name' => 'System',
                'role' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('General123..'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Gym Owner',
                'phone' => '9999999992',
                'gym_name' => 'Power Gym',
                'role' => 2,
                'email_verified_at' => now(),
                'password' => Hash::make('General123..'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff',
                'phone' => '9999999993',
                'gym_name' => 'Power Gym',
                'role' => 3,
                'email_verified_at' => now(),
                'password' => Hash::make('General123..'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'trainer@example.com'],
            [
                'name' => 'Trainer',
                'phone' => '9999999994',
                'gym_name' => 'Power Gym',
                'role' => 4,
                'email_verified_at' => now(),
                'password' => Hash::make('General123..'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'Member',
                'phone' => '9999999995',
                'gym_name' => 'Power Gym',
                'role' => 5,
                'email_verified_at' => now(),
                'password' => Hash::make('General123..'),
            ]
        );

    }
}
