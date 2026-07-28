<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Mail\SendMail;
use Illuminate\Support\Facades\{Log,Mail};

abstract class Controller
{
    use AuthorizesRequests;

     protected function sendMail(string $email,string $subject,string $view,array $data = []): bool {
        try {
            Mail::to($email)->send(
                new SendMail($subject, $view, $data)
            );

            return true;
        } catch (\Throwable $e) {
            Log::error('Mail Send Failed', [
                'email' => $email,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
