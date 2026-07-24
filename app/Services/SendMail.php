<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendMail
{
    /**
     * Send an email using a single optimized entry point.
     *
     * Expected keys:
     * - to (string|array)
     * - subject (string)
     * - view (string)
     * - data (array, optional)
     *
     * @param  array{to: string|array, subject: string, view: string, data?: array}  $mailData
     */
    public function sendMail(array $mailData): bool
    {
        try {
            Mail::send(
                $mailData['view'],
                $mailData['data'] ?? [],
                function ($message) use ($mailData) {
                    $message->to($mailData['to'])
                        ->subject($mailData['subject']);
                }
            );

            return true;
        } catch (Throwable $e) {
            Log::error('Mail send failed: '.$e->getMessage(), [
                'to' => $mailData['to'] ?? null,
                'subject' => $mailData['subject'] ?? null,
                'view' => $mailData['view'] ?? null,
            ]);

            return false;
        }
    }
}
