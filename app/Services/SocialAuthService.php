<?php

namespace App\Services;

use App\Exceptions\SocialAuthException;
use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class SocialAuthService
{
    /**
     * Providers allowed for authentication-only social login.
     *
     * @var list<string>
     */
    public const SUPPORTED_PROVIDERS = ['google', 'facebook'];

    public function __construct(
        private readonly SendMail $mailer,
    ) {}

    /**
     * Ensure the provider is supported before starting OAuth.
     *
     * @throws SocialAuthException
     */
    public function assertSupportedProvider(string $provider): void
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            throw SocialAuthException::invalidProvider($provider);
        }
    }

    /**
     * Redirect the user to the provider's OAuth consent screen.
     *
     * @throws SocialAuthException
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        $this->assertSupportedProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the OAuth callback and return an existing verified local user.
     *
     * Never creates or updates user records — authentication only.
     *
     * @throws SocialAuthException
     */
    public function findUserFromCallback(string $provider): User
    {
        $this->assertSupportedProvider($provider);

        $socialUser = $this->fetchSocialUser($provider);
        $email = $this->extractAndValidateEmail($socialUser);

        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [strtolower($email)])
            ->first();

        if (! $user) {
            throw SocialAuthException::accountNotFound();
        }

        if (! $user->hasVerifiedEmail()) {
            $this->sendVerificationOtp($user);

            throw SocialAuthException::emailNotVerified($user->email);
        }

        return $user;
    }

    /**
     * Generate and email a fresh OTP so the user can verify before login.
     */
    private function sendVerificationOtp(User $user): void
    {
        $otp = $user->generateOtp();

        $this->mailer->sendMail([
            'to' => $user->email,
            'subject' => 'Verify your email - '.config('app.name'),
            'view' => 'emails.registration-otp',
            'data' => [
                'name' => $user->name,
                'otp' => $otp,
                'expiresInMinutes' => User::OTP_EXPIRY_MINUTES,
                'subject' => 'Verify your email - '.config('app.name'),
            ],
        ]);
    }

    /**
     * Fetch and validate the OAuth user profile from the provider.
     *
     * @throws SocialAuthException
     */
    private function fetchSocialUser(string $provider): SocialiteUser
    {
        try {
            return Socialite::driver($provider)->user();
        } catch (Throwable $e) {
            report($e);

            throw SocialAuthException::oauthFailed();
        }
    }

    /**
     * Validate that the OAuth response includes a usable email address.
     *
     * @throws SocialAuthException
     */
    private function extractAndValidateEmail(SocialiteUser $socialUser): string
    {
        $email = $socialUser->getEmail();

        if (! is_string($email) || trim($email) === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw SocialAuthException::missingEmail();
        }

        return trim($email);
    }
}
