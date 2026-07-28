<?php

namespace App\Services;

use App\Models\UserNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public const TYPE_SUCCESS = 'success';
    public const TYPE_WARNING = 'warning';
    public const TYPE_ERROR = 'error';
    public const TYPE_INFORMATION = 'information';

    public const CHANNEL_IN_APP = 'in_app';

    /**
     * Send a notification to the given user (single business action).
     *
     * Business logic lives here; controllers only pass payload.
     *
     * @param int $userId
     * @param array{
     *   title: string,
     *   message: string,
     *   type?: string,
     *   module?: string,
     *   reference_id?: string|int|null,
     *   reference_type?: string|null,
     * } $payload
     * @param array<int, string> $channels
     */
    public function sendToUser(int $userId, array $payload, array $channels = [self::CHANNEL_IN_APP]): bool
    {
        $title = trim((string) ($payload['title'] ?? ''));
        $message = trim((string) ($payload['message'] ?? ''));
        $type = (string) ($payload['type'] ?? self::TYPE_INFORMATION);
        $module = trim((string) ($payload['module'] ?? 'General'));

        $allowedTypes = [
            self::TYPE_SUCCESS,
            self::TYPE_WARNING,
            self::TYPE_ERROR,
            self::TYPE_INFORMATION,
        ];

        if ($title === '' || $message === '') {
            Log::warning('Notification payload missing title/message', ['user_id' => $userId]);
            return false;
        }

        if (! in_array($type, $allowedTypes, true)) {
            $type = self::TYPE_INFORMATION;
        }

        $referenceId = $payload['reference_id'] ?? null;
        $referenceType = $payload['reference_type'] ?? null;

        $referenceId = $referenceId === null ? null : (string) $referenceId;
        $referenceType = $referenceType === null ? null : (string) $referenceType;

        $sent = false;

        foreach ($channels as $channel) {
            if ($channel === self::CHANNEL_IN_APP) {
                $this->storeInApp($userId, [
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                    'module' => $module,
                    'reference_id' => $referenceId,
                    'reference_type' => $referenceType,
                    'channel' => self::CHANNEL_IN_APP,
                ]);

                $sent = true;
                continue;
            }

            // Future-ready: add channel adapters (email, sms, whatsapp, push).
            Log::info('Notification channel not implemented yet', [
                'channel' => $channel,
                'user_id' => $userId,
                'title' => $title,
            ]);
        }

        return $sent;
    }

    /**
     * Convenient static wrapper (matches your requested signature).
     */
    public static function send(
        int $userId,
        string $title,
        string $message,
        string $type = self::TYPE_INFORMATION,
        string $module = 'General',
        $referenceId = null,
        ?string $referenceType = null
    ): bool {
        return app(self::class)->sendToUser(
            $userId,
            [
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'module' => $module,
                'reference_id' => $referenceId,
                'reference_type' => $referenceType,
            ],
            [self::CHANNEL_IN_APP]
        );
    }

    public function unreadCount(int $userId): int
    {
        return UserNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function latest(int $userId, int $limit = 5): Collection
    {
        return UserNotification::query()
            ->where('user_id', $userId)
            ->latest('created_at')
            ->limit(max(1, min(50, $limit)))
            ->get();
    }

    public function list(int $userId, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        return UserNotification::query()
            ->where('user_id', $userId)
            ->latest('created_at')
            ->paginate(max(5, min(50, $perPage)), ['*'], 'page', max(1, $page));
    }

    /**
     * Mark a set of notifications as read.
     *
     * @param array<int, int|string> $notificationIds
     */
    public function markAsRead(int $userId, array $notificationIds): int
    {
        $ids = array_values(array_filter($notificationIds, static fn ($id) => $id !== null && $id !== ''));
        if ($ids === []) {
            return 0;
        }

        return UserNotification::query()
            ->where('user_id', $userId)
            ->whereIn('id', $ids)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(int $userId): int
    {
        return UserNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Transform an Eloquent model into API-ready data.
     */
    public function toDto(UserNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'module' => $notification->module,
            'type' => $notification->type,
            'reference_id' => $notification->reference_id,
            'reference_type' => $notification->reference_type,
            'created_at_human' => $notification->created_at?->diffForHumans(),
            'created_at' => $notification->created_at?->toIso8601String(),
            'is_read' => $notification->read_at !== null,
            'read_at_human' => $notification->read_at?->diffForHumans(),
            'read_at' => $notification->read_at?->toIso8601String(),
        ];
    }

    /**
     * Store current channel (DB/in-app) record.
     *
     * @param array{
     *   title: string,
     *   message: string,
     *   type: string,
     *   module: string,
     *   reference_id: string|null,
     *   reference_type: string|null,
     *   channel: string
     } $data
     */
    private function storeInApp(int $userId, array $data): void
    {
        UserNotification::create([
            'user_id' => $userId,
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'module' => $data['module'],
            'reference_id' => $data['reference_id'],
            'reference_type' => $data['reference_type'],
            'channel' => $data['channel'],
            'read_at' => null,
        ]);
    }
}

