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

        $oldStartDate = $class->start_date;
        $oldStartTime = $class->start_time;
        $oldScheduleDays = $class->schedule_days;
        $oldTrainerId = $class->trainer_id;

        $newScheduleDays = $request->schedule_days ?? [];
        $newStartDate = $request->start_date;
        $newStartTime = $request->start_time;
        $newTrainerId = $request->trainer_id ?: null;

        $isRescheduled = ((string) $oldStartDate !== (string) $newStartDate)
            || ((string) $oldStartTime !== (string) $newStartTime)
            || (json_encode($oldScheduleDays) !== json_encode($newScheduleDays))
            || ((int) $oldTrainerId !== (int) $newTrainerId);

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

        // Notify enrolled members about class update / reschedule
        $bookedMemberIds = GroupClassBooking::query()
            ->where('group_class_id', $class->id)
            ->where('status', 'booked')
            ->pluck('user_id')
            ->unique()
            ->values()
            ->toArray();

        $class->load('trainer');
        $occurrenceText = $this->buildClassOccurrenceText($class);

        foreach ($bookedMemberIds as $memberId) {
            $this->sendNotification((int) $memberId, [
                'title' => $isRescheduled ? 'Class Rescheduled' : 'Class Updated',
                'message' => $isRescheduled
                    ? "Your class '{$class->name}' has been rescheduled {$occurrenceText}."
                    : "Your class '{$class->name}' has been updated. {$occurrenceText}",
                'type' => 'information',
                'module' => 'Class & Schedules',
                'reference_id' => $class->id,
                'reference_type' => 'group_class',
            ]);
        }

        if ($class->trainer) {
            $this->sendNotification((int) $class->trainer->id, [
                'title' => $isRescheduled ? 'Class Rescheduled' : 'Class Updated',
                'message' => 'Your class "'.$class->name.'" has been '.($isRescheduled ? 'rescheduled' : 'updated')." {$occurrenceText}.",
                'type' => 'information',
                'module' => 'Trainer',
                'reference_id' => $class->id,
                'reference_type' => 'group_class',
            ]);
        }

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

        // Capture recipients before cascade deletes bookings
        $bookedMemberIds = GroupClassBooking::query()
            ->where('group_class_id', $class->id)
            ->where('status', 'booked')
            ->pluck('user_id')
            ->unique()
            ->values()
            ->toArray();

        $class->load('trainer');
        $trainerId = $class->trainer_id;

        $class->delete();

        // Notify booked members about cancellation
        foreach ($bookedMemberIds as $memberId) {
            $this->sendNotification((int) $memberId, [
                'title' => 'Class Cancelled',
                'message' => "The class '{$class->name}' has been cancelled. Please contact the gym for alternatives.",
                'type' => 'warning',
                'module' => 'Class & Schedules',
                'reference_id' => $class->id,
                'reference_type' => 'group_class',
            ]);
        }

        if ($trainerId) {
            $this->sendNotification((int) $trainerId, [
                'title' => 'Class Cancelled',
                'message' => "Your class '{$class->name}' has been cancelled.",
                'type' => 'warning',
                'module' => 'Trainer',
                'reference_id' => $class->id,
                'reference_type' => 'group_class',
            ]);
        }

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

        $existing = GroupClassBooking::query()
            ->where('group_class_id', $class->id)
            ->where('user_id', (int) $request->user_id)
            ->first();

        $booking = GroupClassBooking::updateOrCreate(
            ['group_class_id' => $class->id, 'user_id' => $request->user_id],
            ['status' => 'booked', 'booked_at' => now()]
        );

        // Notify only when enrollment is new (or previously not booked)
        $shouldNotify = $existing === null || $existing->status !== 'booked' || (string) $booking->status !== 'booked';
        if ($shouldNotify) {
            $member = $booking->member()->first();
            $class->loadMissing('trainer');
            $trainer = $class->trainer;

            $occurrenceText = $this->buildClassOccurrenceText($class);

            if ($member) {
                $this->sendNotification($member->id, [
                    'title' => 'Enrolled in Class',
                    'message' => "You have been enrolled in '{$class->name}' {$occurrenceText}.",
                    'type' => 'success',
                    'module' => 'Member',
                    'reference_id' => $class->id,
                    'reference_type' => 'group_class',
                ]);
            }

            if ($trainer) {
                $this->sendNotification($trainer->id, [
                    'title' => 'New Member Enrolled',
                    'message' => "A new member has been assigned to your '{$class->name}' class.",
                    'type' => 'information',
                    'module' => 'Trainer',
                    'reference_id' => $class->id,
                    'reference_type' => 'group_class',
                ]);
            }
        }

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

        $booking->loadMissing(['member', 'groupClass.trainer']);
        $member = $booking->member;
        $class = $booking->groupClass;
        $trainer = $class?->trainer;

        $classId = $booking->group_class_id;
        $booking->delete();

        if ($member) {
            $this->sendNotification($member->id, [
                'title' => 'Removed from Class',
                'message' => "You have been removed from the '{$class->name}' class roster.",
                'type' => 'warning',
                'module' => 'Class & Schedules',
                'reference_id' => $classId,
                'reference_type' => 'group_class',
            ]);
        }

        if ($trainer) {
            $this->sendNotification($trainer->id, [
                'title' => 'Member Removed',
                'message' => 'A member has been removed from your '.$class->name.' class.',
                'type' => 'information',
                'module' => 'Trainer',
                'reference_id' => $classId,
                'reference_type' => 'group_class',
            ]);
        }

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

        $booking->loadMissing(['member', 'groupClass.trainer']);
        $member = $booking->member;
        $class = $booking->groupClass;
        $trainer = $class?->trainer;

        $oldStatus = (string) ($booking->status ?? 'booked');

        $request->validate(['status' => 'required|in:booked,attended,cancelled']);

        $newStatus = (string) $request->status;
        $booking->update(['status' => $newStatus]);

        if ($member) {
            if ($newStatus === 'attended') {
                $this->sendNotification($member->id, [
                    'title' => 'Class Attended',
                    'message' => "Great job! You marked attendance for '{$class->name}'.",
                    'type' => 'success',
                    'module' => 'Class & Schedules',
                    'reference_id' => $class->id,
                    'reference_type' => 'group_class',
                ]);
            } elseif ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->sendNotification($member->id, [
                    'title' => 'Booking Cancelled',
                    'message' => "Your booking for '{$class->name}' has been cancelled.",
                    'type' => 'warning',
                    'module' => 'Class & Schedules',
                    'reference_id' => $class->id,
                    'reference_type' => 'group_class',
                ]);
            }
        }

        if ($trainer) {
            if ($newStatus === 'attended') {
                $this->sendNotification($trainer->id, [
                    'title' => 'Member Attendance Recorded',
                    'message' => 'A member marked attendance for your '.$class->name.' class.',
                    'type' => 'information',
                    'module' => 'Trainer',
                    'reference_id' => $class->id,
                    'reference_type' => 'group_class',
                ]);
            } elseif ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->sendNotification($trainer->id, [
                    'title' => 'Booking Cancelled',
                    'message' => 'A member booking was cancelled for your '.$class->name.' class.',
                    'type' => 'information',
                    'module' => 'Trainer',
                    'reference_id' => $class->id,
                    'reference_type' => 'group_class',
                ]);
            }
        }

        return back()->with('success', 'Booking status updated.');
    }

    /**
     * Build a human-friendly occurrence string like:
     * "on Monday at 7:00 AM" (or a safe fallback).
     */
    private function buildClassOccurrenceText(GroupClass $class): string
    {
        $days = is_array($class->schedule_days) ? $class->schedule_days : [];
        $abbr = $days[0] ?? null;

        $dayMap = [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday',
        ];

        $dayLabel = $abbr && isset($dayMap[$abbr]) ? $dayMap[$abbr] : ($abbr ?: 'your class day');

        $timeLabel = null;
        if (! empty($class->start_time)) {
            try {
                $timeLabel = \Carbon\Carbon::parse($class->start_time)->format('g:i A');
            } catch (\Throwable $e) {
                $timeLabel = (string) $class->start_time;
            }
        }

        if ($timeLabel) {
            return 'on '.$dayLabel.' at '.$timeLabel;
        }

        return 'on '.$dayLabel;
    }
}
