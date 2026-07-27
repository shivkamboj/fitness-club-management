<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GroupClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_owner_id',
        'name',
        'description',
        'category',
        'duration_minutes',
        'capacity',
        'schedule_days',
        'start_time',
        'start_date',
        'end_date',
        'trainer_id',
        'location',
        'status',
        'created_by',
    ];

    protected $casts = [
        'schedule_days' => 'array',
        'start_date'    => 'date',
        'end_date'      => 'date',
    ];

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(GroupClassBooking::class);
    }

    public function activeBookings(): HasMany
    {
        return $this->hasMany(GroupClassBooking::class)->where('status', 'booked');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_class_bookings', 'group_class_id', 'user_id')
            ->withPivot('status', 'booked_at')
            ->withTimestamps();
    }

    /**
     * Get formatted schedule days for display.
     */
    public function getScheduleDaysDisplayAttribute(): string
    {
        $days = $this->schedule_days ?? [];
        return count($days) ? implode(', ', $days) : '—';
    }

    /**
     * Get booked seats count.
     */
    public function getBookedCountAttribute(): int
    {
        return $this->bookings()->where('status', 'booked')->count();
    }

    /**
     * Trainer initials for avatar.
     */
    public function getTrainerInitialsAttribute(): string
    {
        if (!$this->trainer) return '?';
        $parts = explode(' ', trim($this->trainer->full_name ?? $this->trainer->name));
        $init = mb_substr($parts[0] ?? '', 0, 1);
        $init .= mb_substr($parts[1] ?? '', 0, 1);
        return mb_strtoupper($init);
    }
}
