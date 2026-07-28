<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    use AuthorizesRequests;

    /**
     * Shared mail helper (array signature).
     *
     * @param array{to: string|array, subject: string, view: string, data?: array} $mailData
     */
    protected function sendMail(array $mailData): bool
    {
        try {
            return app(\App\Services\SendMail::class)->sendMail($mailData);
        } catch (\Throwable $e) {
            Log::error('Mail Send Failed', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Shared in-app notification helper.
     *
     * Controllers should only pass the business payload; storage logic stays in NotificationService.
     *
     * @param array{
     *   title: string,
     *   message: string,
     *   type?: string,
     *   module?: string,
     *   reference_id?: string|int|null,
     *   reference_type?: string|null
     * } $payload
     */
    protected function sendNotification(int $userId, array $payload): bool
    {
        return app(NotificationService::class)->sendToUser($userId, $payload);
    }
}
