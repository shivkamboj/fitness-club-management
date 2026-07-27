<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    public const SOURCE_WALK_IN = 'Walk In';
    public const SOURCE_WEBSITE = 'Website Lead';
    public const SOURCE_FACEBOOK = 'Facebook Lead';
    public const SOURCE_INSTAGRAM = 'Instagram Lead';
    public const SOURCE_PHONE_CALL = 'Phone Call';

    public const SOURCES = [
        self::SOURCE_WALK_IN,
        self::SOURCE_WEBSITE,
        self::SOURCE_FACEBOOK,
        self::SOURCE_INSTAGRAM,
        self::SOURCE_PHONE_CALL,
    ];

    public const STATUS_NEW = 'New';
    public const STATUS_FOLLOW_UP = 'Follow Up';
    public const STATUS_CONVERTED = 'Converted';
    public const STATUS_LOST = 'Lost';

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_FOLLOW_UP,
        self::STATUS_CONVERTED,
        self::STATUS_LOST,
    ];

    protected $fillable = [
        'gym_owner_id',
        'name',
        'phone',
        'email',
        'source',
        'status',
        'follow_up_date',
        'notes',
        'assigned_to',
        'created_by',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }

    public function assignedTrainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get clean WhatsApp URL for phone
     */
    public function getWhatsappUrlAttribute(): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $this->phone);
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '91' . $cleanPhone; // default India prefix if 10 digits
        }
        return 'https://wa.me/' . $cleanPhone;
    }
}
