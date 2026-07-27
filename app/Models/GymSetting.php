<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymSetting extends Model
{
    protected $fillable = ['gym_owner_id', 'key', 'value'];

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }

    /**
     * Get a setting value for a gym owner, with an optional default.
     */
    public static function getValue(int $gymOwnerId, string $key, ?string $default = null): ?string
    {
        return static::where('gym_owner_id', $gymOwnerId)
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    /**
     * Set (upsert) a setting value for a gym owner.
     */
    public static function setValue(int $gymOwnerId, string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['gym_owner_id' => $gymOwnerId, 'key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings for a gym owner as a keyed collection.
     */
    public static function allFor(int $gymOwnerId): array
    {
        return static::where('gym_owner_id', $gymOwnerId)
            ->pluck('value', 'key')
            ->toArray();
    }
}
