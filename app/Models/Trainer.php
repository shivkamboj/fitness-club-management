<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trainer accounts live in the users table with role = ROLE_TRAINER.
 * Soft deletes and ownership scoping are enforced here.
 */
class Trainer extends User
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'users';

    /**
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
        'email_verified_at',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('trainers', function (Builder $builder): void {
            $builder->where('role', self::ROLE_TRAINER);
        });

        static::creating(function (Trainer $trainer): void {
            $trainer->role = self::ROLE_TRAINER;
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'dob' => 'date',
            'joining_date' => 'date',
            'experience' => 'integer',
        ]);
    }

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getFullNameAttribute(): string
    {
        $full = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));

        return $full !== '' ? $full : (string) $this->name;
    }

    public function getInitialsAttribute(): string
    {
        $first = mb_substr((string) ($this->first_name ?: $this->name), 0, 1);
        $last = mb_substr((string) ($this->last_name ?: ''), 0, 1);

        return mb_strtoupper($first.$last);
    }

    public function getProfileImageUrlAttribute(): ?string
    {
        return $this->profile_image
            ? asset('storage/'.$this->profile_image)
            : null;
    }

    public function getBackgroundImageUrlAttribute(): ?string
    {
        return $this->background_image
            ? asset('storage/'.$this->background_image)
            : null;
    }

    public function scopeOwnedBy(Builder $query, int $gymOwnerId): Builder
    {
        return $query->where('gym_owner_id', $gymOwnerId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term): void {
            $like = '%'.$term.'%';
            $q->where('first_name', 'like', $like)
                ->orWhere('last_name', 'like', $like)
                ->orWhere('name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('phone', 'like', $like)
                ->orWhere('specialization', 'like', $like);
        });
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if (! in_array($status, [self::STATUS_ACTIVE, self::STATUS_INACTIVE], true)) {
            return $query;
        }

        return $query->where('status', $status);
    }
}
