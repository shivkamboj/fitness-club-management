<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyForgotOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Traits\SendsMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    use SendsMail;

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gym_name' => $request->gym_name,
                'role' => User::ROLE_GYM_OWNER,
                'password' => $request->password,
            ]);

            // Admin notification: new registration
            $superAdmins = User::query()->where('role', User::ROLE_SUPER_ADMIN)->get();
            foreach ($superAdmins as $admin) {
                $this->sendNotification($admin->id, [
                    'title' => 'New registration',
                    'message' => 'A new gym owner registered: '.$user->name.' ('.$user->email.').',
                    'type' => 'information',
                    'module' => 'Admin',
                    'reference_id' => $user->id,
                    'reference_type' => 'user',
                ]);
            }

            $otp = $user->generateOtp();

            $this->sendOtpEmail(
                user: $user,
                otp: $otp,
                view: 'emails.registration-otp',
                subject: 'Verify your email - '.config('app.name')
            );

            return $this->success('Registration successful. Please verify your email OTP.', null, 201);
        } catch (Throwable $e) {
            report($e);

            return $this->error('Registration failed. Please try again later.', null, 500);
        }
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return $this->success('Email is already verified.');
        }

        $otpError = $user->getOtpError($request->otp);

        if ($otpError !== null) {
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

        $this->sendNotification($user->id, [
            'title' => 'Email verified',
            'message' => 'Thanks for verifying your email. Welcome to '.config('app.name').'!',
            'type' => 'success',
            'module' => 'Authentication',
            'reference_id' => $user->id,
            'reference_type' => 'user',
        ]);

        return $this->success('Email verified successfully.');
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return $this->error('Email is already verified.', null, 422);
        }

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
            user: $user,
            otp: $otp,
            view: 'emails.resend-otp',
            subject: 'Your new verification OTP - '.config('app.name')
        );

        if (! $sent) {
            return $this->error('Failed to send OTP email. Please try again later.', null, 500);
        }

        return $this->success('OTP resent successfully.');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Admin notification: failed login attempt
            $superAdmins = User::query()->where('role', User::ROLE_SUPER_ADMIN)->get();
            foreach ($superAdmins as $admin) {
                $this->sendNotification($admin->id, [
                    'title' => 'Failed login attempt',
                    'message' => 'A login attempt failed for email: '.$request->email.'.',
                    'type' => 'error',
                    'module' => 'Admin',
                    'reference_id' => null,
                    'reference_type' => 'auth',
                ]);
            }

            return $this->error('Invalid email or password.', null, 401);
        }

        if (! $user->hasVerifiedEmail()) {
            return $this->error('Please verify your email before logging in.', null, 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success('Login successful.', [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'gym_name' => $user->gym_name,
                'role' => $user->role,
                'role_name' => $user->role_name,
            ],
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        $otp = $user->generateOtp();

        $sent = $this->sendOtpEmail(
            user: $user,
            otp: $otp,
            view: 'emails.forgot-password-otp',
            subject: 'Password reset OTP - '.config('app.name')
        );

        if (! $sent) {
            return $this->error('Failed to send password reset OTP. Please try again later.', null, 500);
        }

        return $this->success('Password reset OTP sent successfully.');
    }

    public function verifyForgotOtp(VerifyForgotOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        $otpError = $user->getOtpError($request->otp);

        if ($otpError !== null) {
            return response()->json([
                'success' => false,
                'message' => $otpError,
                'otp_expired' => $user->isOtpExpired() || $user->otp === null,
            ], 422);
        }

        return $this->success('OTP verified successfully.');
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        $otpError = $user->getOtpError($request->otp);

        if ($otpError !== null) {
            return response()->json([
                'success' => false,
                'message' => $otpError,
                'errors' => ['otp' => [$otpError]],
                'otp_expired' => $user->isOtpExpired() || $user->otp === null,
            ], 422);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();

        $user->tokens()->delete();

        $this->sendNotification($user->id, [
            'title' => 'Password changed',
            'message' => 'Your password has been updated successfully.',
            'type' => 'success',
            'module' => 'Authentication',
            'reference_id' => $user->id,
            'reference_type' => 'auth',
        ]);

        return $this->success('Password changed successfully.');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return $this->error('Current password is incorrect.', null, 422);
        }

        $user->forceFill([
            'password' => Hash::make($request->new_password),
        ])->save();

        $this->sendNotification($user->id, [
            'title' => 'Password updated',
            'message' => 'Your password has been updated successfully.',
            'type' => 'success',
            'module' => 'Authentication',
            'reference_id' => $user->id,
            'reference_type' => 'auth',
        ]);

        return $this->success('Password updated successfully.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logged out successfully.');
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

    private function success(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status);
    }

    private function error(string $message, mixed $errors = null, int $status = 400): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
