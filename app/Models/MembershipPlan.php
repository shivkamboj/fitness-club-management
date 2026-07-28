<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'gym_owner_id',
        'name',
        'price',
        'duration_months',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_months' => 'integer',
        ];
    }

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'membership_plan_id');
    }

    public function scopeOwnedBy($query, int $gymOwnerId)
    {
        return $query->where('gym_owner_id', $gymOwnerId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
