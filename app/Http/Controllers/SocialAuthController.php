<?php

namespace App\Http\Controllers;

use App\Exceptions\SocialAuthException;
use App\Http\Requests\Auth\SocialProviderRequest;
use App\Services\SocialAuthService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class SocialAuthController extends Controller
{
    public function __construct(
        private readonly SocialAuthService $socialAuth,
    ) {}

    /**
     * Redirect the guest to the OAuth provider login page.
     */
    public function redirect(SocialProviderRequest $request, string $provider): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        try {
            return $this->socialAuth->redirectToProvider($provider);
        } catch (SocialAuthException $e) {
            return redirect()
                ->route('login')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Handle the OAuth callback: log in existing verified users only.
     *
     * Does not create or update user records.
     */
    public function callback(SocialProviderRequest $request, string $provider): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        try {
            $user = $this->socialAuth->findUserFromCallback($provider);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        } catch (SocialAuthException $e) {
            return $this->handleSocialAuthException($e);
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('login')
                ->with('error', 'Social authentication failed. Please try again.');
        }
    }

    private function handleSocialAuthException(SocialAuthException $e): RedirectResponse
    {
        if ($e->requiresEmailVerification() && $e->getEmail()) {
            return redirect()
                ->route('otp.verify', ['email' => $e->getEmail()])
                ->with('info', $e->getMessage());
        }

        return redirect()
            ->route('login')
            ->with('error', $e->getMessage());
    }
}
