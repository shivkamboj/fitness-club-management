<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request and enforce role authorization based on integer role values.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Required role slug ('super-admin' or 'gym-owner')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = (int) ($user->role ?? User::ROLE_MEMBER);

        // Super Admin Check (role must be 1)
        if ($role === 'super-admin') {
            if ($userRole !== User::ROLE_SUPER_ADMIN) {
                return redirect()->route('gym-owner.dashboard')->with('error', 'Access Denied: Super Admin privileges required.');
            }
        }

        // Gym Owner Check (role must be 2, 1, or 4 — Trainers can access gym-owner routes)
        if ($role === 'gym-owner') {
            if ($userRole !== User::ROLE_GYM_OWNER && $userRole !== User::ROLE_SUPER_ADMIN && $userRole !== User::ROLE_TRAINER) {
                return redirect()->route('login')->with('error', 'Access Denied: Gym Owner privileges required.');
            }

            // Trainers must be active
            if ($userRole === User::ROLE_TRAINER && ! $user->isActive()) {
                Auth::logout();

                return redirect()->route('login')->with(
                    'error',
                    'Your account has been disabled. Please contact your Gym Owner.'
                );
            }
        }

        // Trainer Check (role must be 4)
        if ($role === 'trainer') {
            if ($userRole !== User::ROLE_TRAINER) {
                return redirect()->route('login')->with('error', 'Access Denied: Trainer privileges required.');
            }

            if (! $user->isActive()) {
                Auth::logout();

                return redirect()->route('login')->with(
                    'error',
                    'Your account has been disabled. Please contact your Gym Owner.'
                );
            }
        }

        return $next($request);
    }
}
