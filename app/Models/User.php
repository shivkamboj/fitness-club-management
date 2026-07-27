<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const ROLE_MEMBER = 0;

    public const ROLE_SUPER_ADMIN = 1;

    public const ROLE_GYM_OWNER = 2;

    public const ROLE_STAFF = 3;

    public const ROLE_TRAINER = 4;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const OTP_EXPIRY_MINUTES = 10;

    public const OTP_RESEND_COOLDOWN_MINUTES = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'gym_name',
        'role',
        'gym_owner_id',
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
        'otp',
        'otp_expires_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
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
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'integer',
            'dob' => 'date',
            'joining_date' => 'date',
            'experience' => 'integer',
        ];
    }

    public function trainers(): HasMany
    {
        return $this->hasMany(Trainer::class, 'gym_owner_id');
    }

    public function gymOwner()
    {
        return $this->belongsTo(self::class, 'gym_owner_id');
    }

    public function workoutPlans(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(WorkoutPlan::class, 'workout_plan_assignments', 'user_id', 'workout_plan_id')
            ->withPivot('assigned_by', 'assigned_at', 'status')
            ->withTimestamps();
    }

    public function activeWorkoutPlan()
    {
        return $this->belongsToMany(WorkoutPlan::class, 'workout_plan_assignments', 'user_id', 'workout_plan_id')
            ->wherePivot('status', 'active')
            ->withPivot('assigned_by', 'assigned_at', 'status')
            ->withTimestamps()
            ->latest('workout_plan_assignments.created_at');
    }

    public function isSuperAdmin(): bool
    {
        return (int) $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isGymOwner(): bool
    {
        return (int) $this->role === self::ROLE_GYM_OWNER || (int) $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isTrainer(): bool
    {
        return (int) $this->role === self::ROLE_TRAINER;
    }

    public function isActive(): bool
    {
        return ($this->status ?? self::STATUS_ACTIVE) === self::STATUS_ACTIVE;
    }

    public function getRoleNameAttribute(): string
    {
        return match ((int) $this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_GYM_OWNER => 'Gym Owner',
            self::ROLE_STAFF => 'Staff',
            self::ROLE_TRAINER => 'Trainer',
            default => 'Gym Member',
        };
    }

    public function getFullNameAttribute(): string
    {
        $full = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));

        return $full !== '' ? $full : (string) $this->name;
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function generateOtp(): string
    {
        $otp = (string) random_int(100000, 999999);

        $this->forceFill([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ])->save();

        return $otp;
    }

    public function clearOtp(): void
    {
        $this->forceFill([
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();
    }

    public function isOtpValid(?string $otp): bool
    {
        return $this->getOtpError($otp) === null;
    }

    /**
     * Return a user-facing OTP error message, or null when valid.
     */
    public function getOtpError(?string $otp): ?string
    {
        if ($this->otp === null || $this->otp_expires_at === null) {
            return 'No OTP found. Please request a new one.';
        }

        if ($this->otp_expires_at->isPast()) {
            return 'OTP has expired. Please request a new one.';
        }

        if ($otp === null || $otp === '' || ! hash_equals((string) $this->otp, (string) $otp)) {
            return 'Invalid OTP. Please check the code and try again.';
        }

        return null;
    }

    public function isOtpExpired(): bool
    {
        return $this->otp_expires_at !== null && $this->otp_expires_at->isPast();
    }
}
