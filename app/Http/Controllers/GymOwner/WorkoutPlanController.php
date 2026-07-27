<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkoutPlan;
use App\Models\WorkoutPlanExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkoutPlanController extends Controller
{
    /**
     * Display workout plans management index page.
     */
    public function index()
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        // Fetch plans with count of exercises and assigned members
        $plans = WorkoutPlan::where('gym_owner_id', $gymOwnerId)
            ->withCount('exercises')
            ->with(['creator', 'assignedMembers' => function ($query) {
                $query->wherePivot('status', 'active');
            }])
            ->latest()
            ->get();

        // Get members to assign
        $members = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->get();

        return view('gym-owner.workout-plans.index', compact('plans', 'members'));
    }

    /**
     * Show the form for creating a new workout plan.
     */
    public function create()
    {
        return view('gym-owner.workout-plans.create');
    }

    /**
     * Store a newly created workout plan in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal' => 'required|string|max:100',
            'days_per_week' => 'required|integer|min:1|max:7',
            'exercises' => 'required|array',
            'exercises.*.*.exercise_name' => 'required|string|max:255',
            'exercises.*.*.sets' => 'required|integer|min:1',
            'exercises.*.*.reps' => 'required|string|max:50',
            'exercises.*.*.rest' => 'nullable|string|max:50',
            'exercises.*.*.video_link' => 'nullable|url|max:255',
        ], [
            'exercises.*.*.exercise_name.required' => 'Exercise name is required for all exercises.',
            'exercises.*.*.sets.required' => 'Sets is required for all exercises.',
            'exercises.*.*.reps.required' => 'Reps is required for all exercises.',
        ]);

        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        DB::transaction(function () use ($request, $gymOwnerId, $user) {
            $plan = WorkoutPlan::create([
                'gym_owner_id' => $gymOwnerId,
                'name' => $request->name,
                'description' => $request->description,
                'goal' => $request->goal,
                'days_per_week' => $request->days_per_week,
                'created_by' => $user->id,
            ]);

            $order = 0;
            foreach ($request->exercises as $dayNumber => $exercises) {
                foreach ($exercises as $exData) {
                    WorkoutPlanExercise::create([
                        'workout_plan_id' => $plan->id,
                        'day_number' => $dayNumber,
                        'exercise_name' => $exData['exercise_name'],
                        'sets' => $exData['sets'],
                        'reps' => $exData['reps'],
                        'rest' => $exData['rest'] ?? null,
                        'video_link' => $exData['video_link'] ?? null,
                        'order' => $order++,
                    ]);
                }
            }
        });

        return redirect()->route('gym-owner.workout-plans.index')
            ->with('success', 'Workout plan created successfully.');
    }

    /**
     * Show the form for editing the specified workout plan.
     */
    public function edit(WorkoutPlan $workoutPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($workoutPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $workoutPlan->load('exercises');

        // Group exercises by day_number
        $exercisesByDay = $workoutPlan->exercises->groupBy('day_number');

        return view('gym-owner.workout-plans.edit', compact('workoutPlan', 'exercisesByDay'));
    }

    /**
     * Update the specified workout plan in storage.
     */
    public function update(Request $request, WorkoutPlan $workoutPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($workoutPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal' => 'required|string|max:100',
            'days_per_week' => 'required|integer|min:1|max:7',
            'exercises' => 'required|array',
            'exercises.*.*.exercise_name' => 'required|string|max:255',
            'exercises.*.*.sets' => 'required|integer|min:1',
            'exercises.*.*.reps' => 'required|string|max:50',
            'exercises.*.*.rest' => 'nullable|string|max:50',
            'exercises.*.*.video_link' => 'nullable|url|max:255',
        ], [
            'exercises.*.*.exercise_name.required' => 'Exercise name is required for all exercises.',
            'exercises.*.*.sets.required' => 'Sets is required for all exercises.',
            'exercises.*.*.reps.required' => 'Reps is required for all exercises.',
        ]);

        DB::transaction(function () use ($request, $workoutPlan) {
            $workoutPlan->update([
                'name' => $request->name,
                'description' => $request->description,
                'goal' => $request->goal,
                'days_per_week' => $request->days_per_week,
            ]);

            // Recreate exercises
            $workoutPlan->exercises()->delete();

            $order = 0;
            foreach ($request->exercises as $dayNumber => $exercises) {
                foreach ($exercises as $exData) {
                    WorkoutPlanExercise::create([
                        'workout_plan_id' => $workoutPlan->id,
                        'day_number' => $dayNumber,
                        'exercise_name' => $exData['exercise_name'],
                        'sets' => $exData['sets'],
                        'reps' => $exData['reps'],
                        'rest' => $exData['rest'] ?? null,
                        'video_link' => $exData['video_link'] ?? null,
                        'order' => $order++,
                    ]);
                }
            }
        });

        return redirect()->route('gym-owner.workout-plans.index')
            ->with('success', 'Workout plan updated successfully.');
    }

    /**
     * Remove the specified workout plan from storage.
     */
    public function destroy(WorkoutPlan $workoutPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($workoutPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $workoutPlan->delete();

        return redirect()->route('gym-owner.workout-plans.index')
            ->with('success', 'Workout plan deleted successfully.');
    }

    /**
     * Assign the workout plan to members.
     */
    public function assign(Request $request, WorkoutPlan $workoutPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($workoutPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $memberIds = $request->input('members', []);

        DB::transaction(function () use ($workoutPlan, $memberIds, $gymOwnerId) {
            // First, find all current active assignments for this workout plan
            // so we can see who needs to be removed/deactivated
            $currentlyAssigned = DB::table('workout_plan_assignments')
                ->where('workout_plan_id', $workoutPlan->id)
                ->where('status', 'active')
                ->pluck('user_id')
                ->toArray();

            // Deactivate members who are no longer selected
            $toDeactivate = array_diff($currentlyAssigned, $memberIds);
            if (!empty($toDeactivate)) {
                DB::table('workout_plan_assignments')
                    ->where('workout_plan_id', $workoutPlan->id)
                    ->whereIn('user_id', $toDeactivate)
                    ->update(['status' => 'inactive', 'updated_at' => now()]);
            }

            // For newly selected members, deactivate any OTHER active plans they have first,
            // then assign this plan
            $toAssign = array_diff($memberIds, $currentlyAssigned);
            if (!empty($toAssign)) {
                // Deactivate other active plans
                DB::table('workout_plan_assignments')
                    ->whereIn('user_id', $toAssign)
                    ->where('status', 'active')
                    ->update(['status' => 'inactive', 'updated_at' => now()]);

                // Insert new assignments
                $insertData = [];
                foreach ($toAssign as $memberId) {
                    $insertData[] = [
                        'user_id' => $memberId,
                        'workout_plan_id' => $workoutPlan->id,
                        'assigned_by' => Auth::id(),
                        'assigned_at' => now(),
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('workout_plan_assignments')->insert($insertData);
            }
        });

        return redirect()->route('gym-owner.workout-plans.index')
            ->with('success', 'Workout plan assignments updated successfully.');
    }

    /**
     * Display the member's assigned workout plan.
     */
    public function memberWorkouts()
    {
        $member = Auth::user();
        
        // Load active assigned workout plan with exercises
        $activeAssignment = DB::table('workout_plan_assignments')
            ->where('user_id', $member->id)
            ->where('status', 'active')
            ->first();

        $plan = null;
        $completedExerciseIds = [];

        if ($activeAssignment) {
            $plan = WorkoutPlan::with(['exercises', 'creator'])
                ->find($activeAssignment->workout_plan_id);

            if ($plan) {
                // Get exercises completed by the member today
                $completedExerciseIds = DB::table('completed_exercises')
                    ->where('user_id', $member->id)
                    ->whereDate('completed_at', today())
                    ->pluck('workout_plan_exercise_id')
                    ->toArray();
            }
        }

        return view('member.workouts', compact('plan', 'completedExerciseIds'));
    }

    /**
     * Toggle completion of an exercise for the member.
     */
    public function toggleCompleteExercise(Request $request)
    {
        $request->validate([
            'exercise_id' => 'required|exists:workout_plan_exercises,id',
        ]);

        $memberId = Auth::id();
        $exerciseId = $request->input('exercise_id');
        $today = today()->toDateString();

        $existing = DB::table('completed_exercises')
            ->where('user_id', $memberId)
            ->where('workout_plan_exercise_id', $exerciseId)
            ->whereDate('completed_at', $today)
            ->first();

        if ($existing) {
            DB::table('completed_exercises')
                ->where('id', $existing->id)
                ->delete();
            $completed = false;
        } else {
            DB::table('completed_exercises')->insert([
                'user_id' => $memberId,
                'workout_plan_exercise_id' => $exerciseId,
                'completed_at' => $today,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $completed = true;
        }

        return response()->json([
            'success' => true,
            'completed' => $completed,
        ]);
    }
}
