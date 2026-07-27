<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Models\GroupClass;
use App\Models\GroupClassBooking;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupClassController extends Controller
{
    /** Days of the week for schedule selection */
    public const DAYS_OF_WEEK = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    /** Common class categories */
    public const CATEGORIES = [
        'CrossFit', 'Yoga', 'Pilates', 'Zumba', 'HIIT', 'Boxing',
        'Cycling', 'Aerobics', 'Strength', 'Stretching', 'Dance',
        'Kickboxing', 'Martial Arts', 'Swimming', 'Other',
    ];

    /**
     * Display the group classes index with schedules.
     */
    public function index(Request $request)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        $query = GroupClass::where('gym_owner_id', $gymOwnerId)
            ->with(['trainer', 'creator'])
            ->withCount(['bookings as booked_seats_count' => fn ($q) => $q->where('status', 'booked')])
            ->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $classes = $query->get();

        $members = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->get();

        $trainers = Trainer::where('gym_owner_id', $gymOwnerId)
            ->where('status', 'active')
            ->get();

        return view('gym-owner.classes.index', compact('classes', 'members', 'trainers'));
    }

    /**
     * Show the form for creating a new group class.
     */
    public function create()
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        $trainers = Trainer::where('gym_owner_id', $gymOwnerId)
            ->where('status', 'active')
            ->get();

        return view('gym-owner.classes.create', [
            'trainers'   => $trainers,
            'categories' => self::CATEGORIES,
            'daysOfWeek' => self::DAYS_OF_WEEK,
        ]);
    }

    /**
     * Store a newly created group class in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'category'         => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'capacity'         => 'required|integer|min:1|max:500',
            'schedule_days'    => 'nullable|array',
            'schedule_days.*'  => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time'       => 'nullable|date_format:H:i',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'trainer_id'       => 'nullable|exists:users,id',
            'location'         => 'nullable|string|max:255',
            'status'           => 'required|in:active,inactive',
        ]);

        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        GroupClass::create([
            'gym_owner_id'     => $gymOwnerId,
            'name'             => $request->name,
            'description'      => $request->description,
            'category'         => $request->category,
            'duration_minutes' => $request->duration_minutes,
            'capacity'         => $request->capacity,
            'schedule_days'    => $request->schedule_days ?? [],
            'start_time'       => $request->start_time,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'trainer_id'       => $request->trainer_id ?: null,
            'location'         => $request->location,
            'status'           => $request->status,
            'created_by'       => $user->id,
        ]);

        return redirect()->route('gym-owner.classes.index')
            ->with('success', 'Group class created successfully.');
    }

    /**
     * Show the form for editing the specified group class.
     */
    public function edit(GroupClass $class)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($class->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $trainers = Trainer::where('gym_owner_id', $gymOwnerId)
            ->where('status', 'active')
            ->get();

        return view('gym-owner.classes.edit', [
            'class'      => $class,
            'trainers'   => $trainers,
            'categories' => self::CATEGORIES,
            'daysOfWeek' => self::DAYS_OF_WEEK,
        ]);
    }

    /**
     * Update the specified group class in storage.
     */
    public function update(Request $request, GroupClass $class)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($class->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'category'         => 'required|string|max:100',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'capacity'         => 'required|integer|min:1|max:500',
            'schedule_days'    => 'nullable|array',
            'schedule_days.*'  => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'start_time'       => 'nullable|date_format:H:i',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'trainer_id'       => 'nullable|exists:users,id',
            'location'         => 'nullable|string|max:255',
            'status'           => 'required|in:active,inactive',
        ]);

        $class->update([
            'name'             => $request->name,
            'description'      => $request->description,
            'category'         => $request->category,
            'duration_minutes' => $request->duration_minutes,
            'capacity'         => $request->capacity,
            'schedule_days'    => $request->schedule_days ?? [],
            'start_time'       => $request->start_time,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'trainer_id'       => $request->trainer_id ?: null,
            'location'         => $request->location,
            'status'           => $request->status,
        ]);

        return redirect()->route('gym-owner.classes.index')
            ->with('success', 'Group class updated successfully.');
    }

    /**
     * Remove the specified group class from storage.
     */
    public function destroy(GroupClass $class)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($class->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $class->delete();

        return redirect()->route('gym-owner.classes.index')
            ->with('success', 'Group class deleted successfully.');
    }

    /**
     * View the roster (enrolled members) for a group class.
     */
    public function roster(GroupClass $class)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($class->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $bookings = GroupClassBooking::where('group_class_id', $class->id)
            ->with('member')
            ->orderBy('booked_at')
            ->get();

        // Available members who are NOT booked
        $bookedMemberIds = $bookings->where('status', 'booked')->pluck('user_id')->toArray();
        $availableMembers = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->whereNotIn('id', $bookedMemberIds)
            ->get();

        return view('gym-owner.classes.roster', compact('class', 'bookings', 'availableMembers'));
    }

    /**
     * Add a member to the class roster.
     */
    public function addMember(Request $request, GroupClass $class)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($class->gym_owner_id !== $gymOwnerId) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $bookedCount = GroupClassBooking::where('group_class_id', $class->id)
            ->where('status', 'booked')
            ->count();

        if ($bookedCount >= $class->capacity) {
            return back()->with('error', 'This class is already at full capacity.');
        }

        GroupClassBooking::updateOrCreate(
            ['group_class_id' => $class->id, 'user_id' => $request->user_id],
            ['status' => 'booked', 'booked_at' => now()]
        );

        return back()->with('success', 'Member added to class roster.');
    }

    /**
     * Remove a member booking from the roster.
     */
    public function removeMember(GroupClassBooking $booking)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($booking->groupClass->gym_owner_id !== $gymOwnerId) {
            abort(403);
        }

        $classId = $booking->group_class_id;
        $booking->delete();

        return redirect()->route('gym-owner.classes.roster', $classId)
            ->with('success', 'Member removed from class roster.');
    }

    /**
     * Update a booking status (booked / attended / cancelled).
     */
    public function updateBookingStatus(Request $request, GroupClassBooking $booking)
    {
        $user       = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($booking->groupClass->gym_owner_id !== $gymOwnerId) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:booked,attended,cancelled']);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Booking status updated.');
    }
}
