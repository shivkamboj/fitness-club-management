<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Models\GymSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MemberManagementController extends Controller
{
    private function getGymOwnerId(): int
    {
        $user = Auth::user();
        return $user->isGymOwner() ? $user->id : ($user->gym_owner_id ?? $user->id);
    }

    /**
     * Display members list with search & filter.
     */
    public function index(Request $request)
    {
        $gymOwnerId = $this->getGymOwnerId();

        $query = User::where('gym_owner_id', $gymOwnerId)->whereIn('role', [User::ROLE_MEMBER, 5])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->paginate(15)->withQueryString();

        $allMembers = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->get();

        $stats = [
            'total'     => $allMembers->count(),
            'active'    => $allMembers->where('status', User::STATUS_ACTIVE)->count(),
            'inactive'  => $allMembers->where('status', User::STATUS_INACTIVE)->count(),
            'this_month'=> $allMembers->filter(fn ($m) => $m->created_at && $m->created_at->isCurrentMonth())->count(),
        ];

        return view('gym-owner.members.index', compact('members', 'stats'));
    }

    /**
     * Store a new gym member and send WhatsApp & Mail credentials.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email',
            'phone'        => 'required|string|max:30',
            'gender'       => 'nullable|string|in:male,female,other',
            'joining_date' => 'nullable|date',
            'password'     => 'nullable|string|min:6',
        ], [
            'name.required'   => 'Member full name is required.',
            'email.required'  => 'Email address is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.unique'    => '⚠️ This email address is already registered. Please use a different email.',
            'phone.required'  => 'Phone number is required.',
            'password.min'    => 'Password must be at least 6 characters.',
        ]);

        $gymOwnerId = $this->getGymOwnerId();
        $plainPassword = $request->password ?: Str::random(8);

        // Split name into first_name & last_name
        $nameParts = explode(' ', trim($request->name), 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? '';

        $member = User::create([
            'name'         => $request->name,
            'first_name'   => $firstName,
            'last_name'    => $lastName,
            'email'        => strtolower(trim($request->email)),
            'phone'        => trim($request->phone),
            'password'     => Hash::make($plainPassword),
            'role'         => User::ROLE_MEMBER,
            'gym_owner_id' => $gymOwnerId,
            'gender'       => $request->gender,
            'joining_date' => $request->joining_date ?: now()->toDateString(),
            'status'       => User::STATUS_ACTIVE,
        ]);

        $gymName = GymSetting::getValue($gymOwnerId, 'gym_name', Auth::user()->gym_name ?? 'GymForce');

        // 1) Member registered / activated (in-app)
        $this->sendNotification($member->id, [
            'title' => 'Welcome to '.$gymName,
            'message' => 'Your membership has been registered and your account is ready. Log in to start your workout plan.',
            'type' => 'success',
            'module' => 'Member',
            'reference_id' => $member->id,
        ]);

        // 2) Gym Owner notified about new join
        $this->sendNotification($gymOwnerId, [
            'title' => 'New member joined',
            'message' => $member->full_name.' has joined '.$gymName.'.',
            'type' => 'information',
            'module' => 'Gym Owner',
            'reference_id' => $member->id,
        ]);

        // 1. Send Email Notification with credentials
        try {
            $data = [
                'name'      => $member->name,
                'email'     => $member->email,
                'password'  => $plainPassword,
                'gym_name'  => $gymName,
                'login_url' => url('/login'),
            ];

            $this->sendMail([
                'to' => $member->email,
                'subject' => 'Welcome to '.$gymName.' — Your Account Credentials',
                'view' => 'emails.member-credentials',
                'data' => $data,
            ]);

            $mailSent = true;
        } catch (\Throwable $e) {
            Log::error('Failed to send member credentials email: ' . $e->getMessage());
            $mailSent = false;
        }

        // 2. Generate WhatsApp link with credentials
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $member->phone);
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '91' . $cleanPhone;
        }

        $loginUrl = url('/login');
        $waMessage = "Hi {$member->full_name}, welcome to {$gymName}! 🏋️‍♂️\n\nYour account has been created. Here are your login details:\n📧 Email: {$member->email}\n🔑 Password: {$plainPassword}\n🌐 Login: {$loginUrl}\n\nHappy Workout!";
        $whatsappUrl = 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($waMessage);

        return redirect()->route('gym-owner.members.index')
            ->with('success', 'Member added successfully!' . ($mailSent ? ' Credentials email dispatched.' : ''))
            ->with('created_credentials', [
                'name'         => $member->full_name,
                'email'        => $member->email,
                'phone'        => $member->phone,
                'password'     => $plainPassword,
                'whatsapp_url' => $whatsappUrl,
            ]);
    }

    /**
     * Update specified member.
     */
    public function update(Request $request, User $member)
    {
        $gymOwnerId = $this->getGymOwnerId();
        if ((int)$member->gym_owner_id !== (int)$gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $previousStatus = (string) ($member->status ?? User::STATUS_ACTIVE);

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $member->id,
            'phone'        => 'required|string|max:30',
            'gender'       => 'nullable|string|in:male,female,other',
            'joining_date' => 'nullable|date',
            'status'       => 'required|in:active,inactive',
            'password'     => 'nullable|string|min:6',
        ]);

        $nameParts = explode(' ', trim($request->name), 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? '';

        $data = [
            'name'         => $request->name,
            'first_name'   => $firstName,
            'last_name'    => $lastName,
            'email'        => strtolower(trim($request->email)),
            'phone'        => trim($request->phone),
            'gender'       => $request->gender,
            'joining_date' => $request->joining_date,
            'status'       => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        // Member activated / deactivated (in-app) — only when status changes
        if ((string) $member->status !== $previousStatus) {
            $gymName = GymSetting::getValue($gymOwnerId, 'gym_name', Auth::user()->gym_name ?? 'GymForce');

            if ((string) $member->status === User::STATUS_ACTIVE) {
                $this->sendNotification($member->id, [
                    'title' => 'Membership Approved & Activated',
                    'message' => 'Your membership on '.$gymName.' is now active. Enjoy your workouts!',
                    'type' => 'success',
                    'module' => 'Member',
                    'reference_id' => $member->id,
                ]);
            } else {
                $this->sendNotification($member->id, [
                    'title' => 'Membership Deactivated',
                    'message' => 'Your membership on '.$gymName.' has been deactivated. Please contact the gym for details.',
                    'type' => 'warning',
                    'module' => 'Member',
                    'reference_id' => $member->id,
                ]);
            }
        }

        return redirect()->route('gym-owner.members.index')
            ->with('success', 'Member details updated successfully.');
    }

    /**
     * Delete member account.
     */
    public function destroy(User $member)
    {
        $gymOwnerId = $this->getGymOwnerId();

        if ((int)$member->gym_owner_id !== (int)$gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $member->delete();

        return redirect()->route('gym-owner.members.index')
            ->with('success', 'Member deleted successfully.');
    }
}
