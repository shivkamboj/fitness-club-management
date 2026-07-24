<?php

namespace App\Exceptions;

use Exception;

class SocialAuthException extends Exception
{
    private ?string $email = null;

    private bool $requiresEmailVerification = false;

    public static function accountNotFound(): self
    {
        return new self(
            'No account found with this email. Please register first.'
        );
    }

    public static function emailNotVerified(string $email): self
    {
        $exception = new self(
            'Please verify your email with the OTP we sent before logging in.'
        );
        $exception->email = $email;
        $exception->requiresEmailVerification = true;

        return $exception;
    }

    public static function invalidProvider(string $provider): self
    {
        return new self("Unsupported social login provider: {$provider}.");
    }

    public static function missingEmail(): self
    {
        return new self(
            'Unable to retrieve a valid email from the social provider. Please try again or use email login.'
        );
    }

    public static function oauthFailed(?string $message = null): self
    {
        return new self($message ?? 'Social authentication failed. Please try again.');
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function requiresEmailVerification(): bool
    {
        return $this->requiresEmailVerification;
    }
}
