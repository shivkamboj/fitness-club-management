<?php

namespace App\Traits;

use App\Services\SendMail;

trait SendsMail
{
    /**
     * Send an email via the shared SendMail service.
     *
     * @param  array{to: string|array, subject: string, view: string, data?: array}  $mailData
     */
    protected function sendMail(array $mailData): bool
    {
        return app(SendMail::class)->sendMail($mailData);
    }
}
