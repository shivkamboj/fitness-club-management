<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role Constants matching database schema
    const ROLE_MEMBER      = 0;
    const ROLE_SUPER_ADMIN = 1;
    const ROLE_GYM_OWNER   = 2;
    const ROLE_STAFF       = 3;
    const ROLE_TRAINER     = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gym_name',
        'role', // 1 = Super Admin, 2 = Gym Owner, etc.
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'integer',
        ];
    }

    /**
     * Check if user is platform Super Admin (role === 1).
     */
    public function isSuperAdmin(): bool
    {
        return (int) $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is a Gym Owner subscriber (role === 2).
     */
    public function isGymOwner(): bool
    {
        return (int) $this->role === self::ROLE_GYM_OWNER || (int) $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Get human readable role name.
     */
    public function getRoleNameAttribute(): string
    {
        return match ((int) $this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_GYM_OWNER   => 'Gym Owner',
            self::ROLE_STAFF       => 'Staff',
            self::ROLE_TRAINER     => 'Trainer',
            default                => 'Gym Member',
        };
    }
}
