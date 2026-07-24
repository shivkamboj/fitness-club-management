<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\SendsMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Throwable;

class AuthController extends Controller
{
    use SendsMail;

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function jsonSuccess(string $message, string $redirect = '/'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    private function jsonError(string $message, array $errors = [], int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    private function sendOtpEmail(User $user, string $otp, string $view, string $subject): bool
    {
        return $this->sendMail([
            'to' => $user->email,
            'subject' => $subject,
            'view' => $view,
            'data' => [
                'name' => $user->name,
                'otp' => $otp,
                'expiresInMinutes' => User::OTP_EXPIRY_MINUTES,
                'subject' => $subject,
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Login
    // ─────────────────────────────────────────────────────────────────────────

    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return $this->jsonError('Invalid email or password. Please try again.', [], 401);
        }

        if (! $user->isActive()) {
            return $this->jsonError(
                'Your account has been disabled. Please contact your Gym Owner.',
                [],
                403
            );
        }

        if (! $user->hasVerifiedEmail()) {
            $user->generateOtp();

            return response()->json([
                'success' => false,
                'message' => 'Please verify your email before logging in.',
                'redirect' => route('otp.verify', ['email' => $user->email]),
            ], 403);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->jsonSuccess('Welcome back! Redirecting…', $this->dashboardRouteFor($user));
    }

    private function dashboardRouteFor(User $user): string
    {
        if ($user->isSuperAdmin()) {
            return route('super-admin.dashboard');
        }

        if ($user->isTrainer()) {
            return route('trainer.dashboard');
        }

        if ($user->isGymOwner()) {
            return route('gym-owner.dashboard');
        }

        return route('dashboard');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Register
    // ─────────────────────────────────────────────────────────────────────────

    public function registerForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gym_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password_confirmation' => ['required'],
            'terms' => ['accepted'],
        ], [
            'email.unique' => 'This email is already registered. Try logging in instead.',
            'terms.accepted' => 'You must agree to the Terms of Service and Privacy Policy.',
            'password.confirmed' => 'The passwords do not match.',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'gym_name' => $validated['gym_name'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => User::ROLE_GYM_OWNER,
                'status' => User::STATUS_ACTIVE,
                'password' => $validated['password'],
            ]);

            $otp = $user->generateOtp();

            $this->sendOtpEmail(
                $user,
                $otp,
                'emails.registration-otp',
                'Verify your email - '.config('app.name')
            );

            return $this->jsonSuccess(
                'Registration successful. Please verify your email OTP.',
                route('otp.verify', ['email' => $user->email])
            );
        } catch (Throwable $e) {
            report($e);

            return $this->jsonError('Registration failed. Please try again later.', [], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Email OTP Verification
    // ─────────────────────────────────────────────────────────────────────────

    public function verifyOtpForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $email = $request->query('email', session('otp_email'));

        if (! $email) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'purpose' => 'register',
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'digits:6'],
        ], [
            'email.exists' => 'No account found with this email address.',
            'otp.digits' => 'OTP must be exactly 6 digits.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user->hasVerifiedEmail()) {
            return $this->jsonSuccess('Email is already verified.', route('login'));
        }

        if (! $user->isOtpValid($validated['otp'])) {
            $otpError = $user->getOtpError($validated['otp']);

            return response()->json([
                'success' => false,
                'message' => $otpError,
                'errors' => ['otp' => [$otpError]],
                'otp_expired' => $user->isOtpExpired() || $user->otp === null,
            ], 422);
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();

        Auth::login($user);
        $request->session()->regenerate();

        return $this->jsonSuccess('Email verified successfully.', route('dashboard'));
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user->hasVerifiedEmail()) {
            return $this->jsonError('Email is already verified.', [], 422);
        }

        // Enforce 5-minute cooldown between OTP sends
        if ($user->otp_expires_at && $user->otp_expires_at->isFuture()) {
            $availableAt = $user->otp_expires_at
                ->copy()
                ->subMinutes(User::OTP_EXPIRY_MINUTES - User::OTP_RESEND_COOLDOWN_MINUTES);

            if ($availableAt->isFuture()) {
                $retryAfter = max(1, (int) now()->diffInSeconds($availableAt, false));

                return response()->json([
                    'success' => false,
                    'message' => 'Please wait before requesting another OTP.',
                    'retry_after' => $retryAfter,
                ], 429);
            }
        }

        $otp = $user->generateOtp();

        $sent = $this->sendOtpEmail(
            $user,
            $otp,
            'emails.resend-otp',
            'Your new verification OTP - '.config('app.name')
        );

        if (! $sent) {
            return $this->jsonError('Failed to send OTP email. Please try again later.', [], 500);
        }

        return $this->jsonSuccess('OTP resent successfully.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Forgot / Reset Password
    // ─────────────────────────────────────────────────────────────────────────

    public function forgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $user = User::where('email', $validated['email'])->first();
        $otp = $user->generateOtp();

        $sent = $this->sendOtpEmail(
            $user,
            $otp,
            'emails.forgot-password-otp',
            'Password reset OTP - '.config('app.name')
        );

        if (! $sent) {
            return $this->jsonError('Failed to send password reset OTP. Please try again later.', [], 500);
        }

        $request->session()->put('password_reset_email', $user->email);

        return $this->jsonSuccess(
            'Password reset OTP sent successfully.',
            route('password.reset', ['email' => $user->email])
        );
    }

    public function resetPasswordForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $email = $request->query('email', session('password_reset_email'));

        if (! $email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('password.request')
                ->with('error', 'No account found with this email address.');
        }

        $otpExpired = $user->isOtpExpired() || $user->otp === null;
        $expiresAt = $user->otp_expires_at?->timestamp;

        return view('auth.reset-password', [
            'email' => $email,
            'otpExpired' => $otpExpired,
            'otpExpiresAt' => $expiresAt,
            'otpExpiryMinutes' => User::OTP_EXPIRY_MINUTES,
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password_confirmation' => ['required'],
        ], [
            'email.exists' => 'No account found with this email address.',
            'otp.digits' => 'OTP must be exactly 6 digits.',
            'password.confirmed' => 'The passwords do not match.',
        ]);

        $user = User::where('email', $validated['email'])->first();
        $otpError = $user->getOtpError($validated['otp']);

        if ($otpError !== null) {
            return response()->json([
                'success' => false,
                'message' => $otpError,
                'errors' => ['otp' => [$otpError]],
                'otp_expired' => $user->isOtpExpired() || $user->otp === null,
                'redirect' => ($user->isOtpExpired() || $user->otp === null)
                    ? route('password.request')
                    : null,
            ], 422);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();

        $user->tokens()->delete();
        $request->session()->forget('password_reset_email');

        return $this->jsonSuccess('Password changed successfully.', route('login'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Logout
    // ─────────────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Contact
    // ─────────────────────────────────────────────────────────────────────────

    public function contact(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'gym_name' => ['nullable', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:30'],
                'message' => ['nullable', 'string', 'max:2000'],
            ]);

            return back()->with('success', 'Thanks! We will get back to you within 24 hours.');
        }

        return view('auth.login');
    }
}
