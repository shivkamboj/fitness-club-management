<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_templates';

    protected $fillable = [
        'gym_owner_id',
        'event_key',
        'event_title',
        'is_enabled',
        'message_template',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }

    /**
     * Default event configurations
     */
    public static function getDefaultEvents(): array
    {
        return [
            'welcome' => [
                'title' => 'Welcome Message',
                'description' => 'Sent to new members when they join the gym.',
                'icon' => 'fa-hand-wave',
                'placeholders' => ['{member_name}', '{gym_name}', '{plan_name}', '{joining_date}'],
                'template' => "Hi {member_name}, welcome to {gym_name}! 💪 We are thrilled to have you onboard. Your {plan_name} membership is now active. Let's achieve your fitness goals together!"
            ],
            'renewal_reminder' => [
                'title' => 'Renewal Reminder',
                'description' => 'Sent to members when their membership expiry date is near.',
                'icon' => 'fa-calendar-check',
                'placeholders' => ['{member_name}', '{gym_name}', '{plan_name}', '{due_date}'],
                'template' => "Hello {member_name}, your membership for {plan_name} at {gym_name} is expiring on {due_date}. Renew today to continue your workout streak! 🏋️‍♂️"
            ],
            'payment_reminder' => [
                'title' => 'Payment Reminder',
                'description' => 'Sent for pending fees or installment payment due dates.',
                'icon' => 'fa-credit-card',
                'placeholders' => ['{member_name}', '{gym_name}', '{amount}', '{due_date}'],
                'template' => "Dear {member_name}, this is a reminder that your payment of {amount} at {gym_name} is due on {due_date}. Please clear your dues at the reception or online."
            ],
            'birthday_wishes' => [
                'title' => 'Birthday Wishes',
                'description' => 'Automated warm birthday greetings sent to members on their birthday.',
                'icon' => 'fa-cake-candles',
                'placeholders' => ['{member_name}', '{gym_name}'],
                'template' => "Happy Birthday {member_name}! 🎉🎂 Wishing you a fit, healthy and powerful year ahead from all of us at {gym_name}. Enjoy your special day!"
            ],
            'offer_notifications' => [
                'title' => 'Offer Notifications',
                'description' => 'Sent to announce special discounts, festival offers, and deals.',
                'icon' => 'fa-bullhorn',
                'placeholders' => ['{member_name}', '{gym_name}', '{offer_details}'],
                'template' => "Special Offer at {gym_name}! 🔥 {offer_details}. Don't miss out on this limited period deal. Visit the front desk today to claim!"
            ],
            'workout_reminder' => [
                'title' => 'Workout Reminder',
                'description' => 'Sent to remind members of their daily workout routine or scheduled session.',
                'icon' => 'fa-dumbbell',
                'placeholders' => ['{member_name}', '{gym_name}', '{workout_plan}'],
                'template' => "Hey {member_name}, your workout session ({workout_plan}) is scheduled today at {gym_name}! Time to sweat and crush your goals! 💪🏼"
            ],
        ];
    }

    /**
     * Get or initialize templates for a gym owner.
     */
    public static function getTemplatesForGym(int $gymOwnerId): array
    {
        $defaults = static::getDefaultEvents();
        $existing = static::where('gym_owner_id', $gymOwnerId)->get()->keyBy('event_key');

        $result = [];
        foreach ($defaults as $key => $meta) {
            if ($existing->has($key)) {
                $item = $existing->get($key);
                $result[$key] = [
                    'id'               => $item->id,
                    'key'              => $key,
                    'title'            => $item->event_title,
                    'description'      => $meta['description'],
                    'icon'             => $meta['icon'],
                    'placeholders'     => $meta['placeholders'],
                    'is_enabled'       => (bool) $item->is_enabled,
                    'message_template' => $item->message_template,
                ];
            } else {
                $result[$key] = [
                    'id'               => null,
                    'key'              => $key,
                    'title'            => $meta['title'],
                    'description'      => $meta['description'],
                    'icon'             => $meta['icon'],
                    'placeholders'     => $meta['placeholders'],
                    'is_enabled'       => true,
                    'message_template' => $meta['template'],
                ];
            }
        }

        return $result;
    }
}
