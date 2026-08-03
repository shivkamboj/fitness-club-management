<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GymSetting;
use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GymManagementController extends Controller
{
    /**
     * Display list of gym owners and registered gyms with filters & statistics.
     */
    public function index(Request $request)
    {
        $query = User::where('role', User::ROLE_GYM_OWNER)
            ->withCount([
                'members' => fn ($q) => $q->whereIn('role', [User::ROLE_MEMBER, 5]),
                'trainers',
            ])
            ->latest();

        // Search filter (Gym Name, Owner Name, Email, Phone)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('gym_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $gyms = $query->paginate(10)->withQueryString();

        // Attach dynamic attributes (city & active plans count) to paginated gyms
        $gyms->getCollection()->transform(function (User $gymOwner) {
            $gymOwner->city = GymSetting::getValue($gymOwner->id, 'city')
                ?? GymSetting::getValue($gymOwner->id, 'address')
                ?? '—';
            $gymOwner->active_plans_count = MembershipPlan::ownedBy($gymOwner->id)->active()->count();
            return $gymOwner;
        });

        // Global Statistics
        $allGymOwners = User::where('role', User::ROLE_GYM_OWNER)->get();
        $stats = [
            'total' => $allGymOwners->count(),
            'active' => $allGymOwners->where('status', User::STATUS_ACTIVE)->count(),
            'inactive' => $allGymOwners->where('status', User::STATUS_INACTIVE)->count(),
            'total_members' => User::whereIn('role', [User::ROLE_MEMBER, 5])->count(),
            'new_this_month' => $allGymOwners->filter(fn ($g) => $g->created_at && $g->created_at->isCurrentMonth())->count(),
        ];

        return view('super-admin.gyms.index', compact('gyms', 'stats'));
    }

    /**
     * Store a newly created Gym Owner in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gym_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        $gymOwner = User::create([
            'gym_name' => $validated['gym_name'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_GYM_OWNER,
            'status' => $validated['status'],
            'email_verified_at' => now(),
        ]);

        if (! empty($validated['city'])) {
            GymSetting::setValue($gymOwner->id, 'city', $validated['city']);
        }

        return redirect()->route('super-admin.gyms.index')
            ->with('success', "Gym center '{$gymOwner->gym_name}' registered successfully!");
    }

    /**
     * Display details for a specific Gym Owner (AJAX / Modal response).
     */
    public function show(User $gymOwner)
    {
        if ((int) $gymOwner->role !== User::ROLE_GYM_OWNER) {
            return response()->json(['error' => 'Invalid gym owner'], 404);
        }

        $membersCount = User::where('gym_owner_id', $gymOwner->id)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->count();

        $trainersCount = Trainer::where('gym_owner_id', $gymOwner->id)->count();
        $plansCount = MembershipPlan::ownedBy($gymOwner->id)->count();

        $city = GymSetting::getValue($gymOwner->id, 'city')
            ?? GymSetting::getValue($gymOwner->id, 'address')
            ?? 'Not specified';

        return response()->json([
            'id' => $gymOwner->id,
            'gym_name' => $gymOwner->gym_name ?: ($gymOwner->full_name.' Gym'),
            'name' => $gymOwner->name,
            'full_name' => $gymOwner->full_name,
            'email' => $gymOwner->email,
            'phone' => $gymOwner->phone ?? 'N/A',
            'city' => $city,
            'status' => $gymOwner->status ?? 'active',
            'members_count' => $membersCount,
            'trainers_count' => $trainersCount,
            'plans_count' => $plansCount,
            'registered_at' => $gymOwner->created_at?->format('F d, Y - h:i A') ?? 'N/A',
        ]);
    }

    /**
     * Update an existing Gym Owner details.
     */
    public function update(Request $request, User $gymOwner)
    {
        if ((int) $gymOwner->role !== User::ROLE_GYM_OWNER) {
            abort(404);
        }

        $validated = $request->validate([
            'gym_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$gymOwner->id,
            'phone' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        $gymOwner->gym_name = $validated['gym_name'];
        $gymOwner->name = $validated['name'];
        $gymOwner->email = $validated['email'];
        $gymOwner->phone = $validated['phone'] ?? null;
        $gymOwner->status = $validated['status'];

        if (! empty($validated['password'])) {
            $gymOwner->password = Hash::make($validated['password']);
        }

        $gymOwner->save();

        if (isset($validated['city'])) {
            GymSetting::setValue($gymOwner->id, 'city', $validated['city']);
        }

        return redirect()->route('super-admin.gyms.index')
            ->with('success', "Gym owner details for '{$gymOwner->gym_name}' updated successfully!");
    }

    /**
     * Toggle status (Active / Inactive) of a gym owner.
     */
    public function toggleStatus(Request $request, User $gymOwner)
    {
        if ((int) $gymOwner->role !== User::ROLE_GYM_OWNER) {
            return response()->json(['error' => 'Invalid gym owner'], 404);
        }

        $newStatus = $gymOwner->status === User::STATUS_ACTIVE ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
        $gymOwner->status = $newStatus;
        $gymOwner->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => "Gym status changed to {$newStatus}.",
            ]);
        }

        return redirect()->back()->with('success', "Gym status updated to {$newStatus}.");
    }

    /**
     * Remove the specified gym owner from storage.
     */
    public function destroy(Request $request, User $gymOwner)
    {
        if ((int) $gymOwner->role !== User::ROLE_GYM_OWNER) {
            abort(404);
        }

        $gymName = $gymOwner->gym_name ?: $gymOwner->name;
        $gymOwner->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Gym center '{$gymName}' has been deleted.",
            ]);
        }

        return redirect()->route('super-admin.gyms.index')
            ->with('success', "Gym center '{$gymName}' has been removed successfully.");
    }
}
