<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Return a consistent JSON success response for AJAX requests.
     */
    private function jsonSuccess(string $message, string $redirect = '/'): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success'  => true,
            'message'  => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Return a consistent JSON error response for AJAX requests.
     * Optionally includes field-level validation errors.
     */
    private function jsonError(string $message, array $errors = [], int $status = 422): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Login
    // ─────────────────────────────────────────────────────────────────────────

    /** Show login form */
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /** Handle AJAX login POST */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return $this->jsonError('Invalid email or password. Please try again.', [], 401);
        }

        $request->session()->regenerate();

        return $this->jsonSuccess('Welcome back! Redirecting…', route('dashboard'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Register
    // ─────────────────────────────────────────────────────────────────────────

    /** Show register form */
    public function registerForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /** Handle AJAX register POST */
    public function register(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'gym_name'              => ['nullable', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'                 => ['required', 'string', 'max:30'],
            'password'              => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'password_confirmation' => ['required'],
            'terms'                 => ['accepted'],
        ], [
            'email.unique'   => 'This email is already registered. Try logging in instead.',
            'terms.accepted' => 'You must agree to the Terms of Service and Privacy Policy.',
            'password.confirmed' => 'The passwords do not match.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'gym_name' => $validated['gym_name'] ?? null,
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return $this->jsonSuccess('Account created! Welcome aboard 🎉', route('dashboard'));
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
                'name'     => ['required', 'string', 'max:255'],
                'gym_name' => ['nullable', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255'],
                'phone'    => ['required', 'string', 'max:30'],
                'message'  => ['nullable', 'string', 'max:2000'],
            ]);

            // TODO: store lead in DB, send email/notification, etc.

            return back()->with('success', 'Thanks! We will get back to you within 24 hours.');
        }

        return view('auth.login');
    }
}
