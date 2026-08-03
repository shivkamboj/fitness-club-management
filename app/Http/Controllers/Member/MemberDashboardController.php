<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\DietPlan;
use App\Models\GroupClass;
use App\Models\GroupClassBooking;
use App\Models\WorkoutPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberDashboardController extends Controller
{
    /**
     * Display Member Dashboard overview.
     */
    public function index()
    {
        $member = Auth::user();

        // 1. Gym & Membership Info
        $gymName = $member->gymOwner?->gym_name
            ?: ($member->gymOwner?->full_name ? $member->gymOwner->full_name . ' Gym' : ($member->gym_name ?: 'My Gym Center'));
        $membershipPlan = $member->membershipPlan;
        $expiresAt = $member->membership_expires_at;
        $daysRemaining = $expiresAt ? (int) now()->startOfDay()->diffInDays($expiresAt->startOfDay(), false) : null;

        // 2. Active Workout Plan
        $activeWorkoutAssignment = DB::table('workout_plan_assignments')
            ->where('user_id', $member->id)
            ->where('status', 'active')
            ->first();

        $activeWorkoutPlan = null;
        $todayCompletedExercisesCount = 0;
        if ($activeWorkoutAssignment) {
            $activeWorkoutPlan = WorkoutPlan::with('exercises')
                ->find($activeWorkoutAssignment->workout_plan_id);

            if ($activeWorkoutPlan) {
                $todayCompletedExercisesCount = DB::table('completed_exercises')
                    ->where('user_id', $member->id)
                    ->whereDate('completed_at', today())
                    ->count();
            }
        }

        // 3. Active Diet Plan
        $activeDietAssignment = DB::table('diet_plan_assignments')
            ->where('user_id', $member->id)
            ->where('status', 'active')
            ->first();

        $activeDietPlan = null;
        if ($activeDietAssignment) {
            $activeDietPlan = DietPlan::with('meals')->find($activeDietAssignment->diet_plan_id);
        }

        // 4. Upcoming Group Class Bookings
        $upcomingBookings = GroupClassBooking::where('user_id', $member->id)
            ->where('status', 'booked')
            ->with(['groupClass.trainer'])
            ->latest('booked_at')
            ->take(5)
            ->get();

        return view('member.dashboard', compact(
            'member',
            'gymName',
            'membershipPlan',
            'expiresAt',
            'daysRemaining',
            'activeWorkoutPlan',
            'todayCompletedExercisesCount',
            'activeDietPlan',
            'upcomingBookings'
        ));
    }

    /**
     * Display member's assigned diet plan details.
     */
    public function dietPlan()
    {
        $member = Auth::user();

        $activeAssignment = DB::table('diet_plan_assignments')
            ->where('user_id', $member->id)
            ->where('status', 'active')
            ->first();

        $dietPlan = null;
        if ($activeAssignment) {
            $dietPlan = DietPlan::with(['meals', 'creator'])->find($activeAssignment->diet_plan_id);
        }

        return view('member.diet-plan', compact('dietPlan'));
    }

    /**
     * Display available group classes & member's current bookings.
     */
    public function classes()
    {
        $member = Auth::user();
        $gymOwnerId = $member->gym_owner_id;

        // Group classes created for member's gym center
        $availableClasses = GroupClass::where('gym_owner_id', $gymOwnerId)
            ->where('status', 'active')
            ->with(['trainer', 'bookings'])
            ->latest()
            ->get();

        // Member's bookings
        $myBookings = GroupClassBooking::where('user_id', $member->id)
            ->with(['groupClass.trainer'])
            ->latest()
            ->get();

        $bookedClassIds = $myBookings->where('status', 'booked')->pluck('group_class_id')->toArray();

        return view('member.classes', compact('availableClasses', 'myBookings', 'bookedClassIds'));
    }

    /**
     * Book a seat in a group class.
     */
    public function bookClass(Request $request, GroupClass $groupClass)
    {
        $member = Auth::user();

        // Verify member belongs to same gym
        if ($groupClass->gym_owner_id !== $member->gym_owner_id) {
            return redirect()->back()->with('error', 'You cannot book classes from another gym center.');
        }

        // Check if already booked
        $existingBooking = GroupClassBooking::where('group_class_id', $groupClass->id)
            ->where('user_id', $member->id)
            ->where('status', 'booked')
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('warning', 'You have already booked a seat for this class.');
        }

        // Check capacity
        if ($groupClass->booked_count >= $groupClass->capacity) {
            return redirect()->back()->with('error', 'Sorry, this class has reached maximum capacity.');
        }

        GroupClassBooking::create([
            'group_class_id' => $groupClass->id,
            'user_id' => $member->id,
            'status' => 'booked',
            'booked_at' => now(),
        ]);

        return redirect()->back()->with('success', "Seat booked successfully for '{$groupClass->name}'!");
    }

    /**
     * Cancel a group class booking.
     */
    public function cancelBooking(Request $request, GroupClassBooking $booking)
    {
        $member = Auth::user();

        if ($booking->user_id !== $member->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Class booking cancelled.');
    }
}
