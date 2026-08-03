<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display overall user directory across all gyms with search, filtering & pagination.
     */
    public function index(Request $request)
    {
        $query = User::with('gymOwner')->latest();

        // Search Filter (Name, Email, Phone, Gym Name)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('gym_name', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role') && $request->role !== '') {
            $role = $request->role;
            if ($role === 'member') {
                $query->whereIn('role', [User::ROLE_MEMBER, 5]);
            } else {
                $query->where('role', (int) $role);
            }
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $perPage = max(1, (int) $request->input('per_page', 10));
        $users = $query->paginate($perPage)->withQueryString();

        // Platform Statistics
        $stats = [
            'total' => User::count(),
            'super_admins' => User::where('role', User::ROLE_SUPER_ADMIN)->count(),
            'gym_owners' => User::where('role', User::ROLE_GYM_OWNER)->count(),
            'trainers' => User::where('role', User::ROLE_TRAINER)->count(),
            'members' => User::whereIn('role', [User::ROLE_MEMBER, 5])->count(),
        ];

        return view('super-admin.users.index', compact('users', 'stats'));
    }

    /**
     * Toggle status (Active / Inactive) of a platform user.
     */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = $user->status === User::STATUS_ACTIVE ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
        $user->status = $newStatus;
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => "User status updated to {$newStatus}.",
            ]);
        }

        return redirect()->back()->with('success', "User status updated to {$newStatus}.");
    }

    /**
     * Delete a platform user.
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->full_name ?: $user->name;
        $user->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "User '{$userName}' removed successfully.",
            ]);
        }

        return redirect()->route('super-admin.users.index')
            ->with('success', "User '{$userName}' has been removed.");
    }
}
