<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Models\DietPlan;
use App\Models\DietPlanMeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DietPlanController extends Controller
{
    /**
     * Display diet plans management index page.
     */
    public function index()
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        $plans = DietPlan::where('gym_owner_id', $gymOwnerId)
            ->withCount('meals')
            ->with(['creator', 'assignedMembers' => function ($query) {
                $query->wherePivot('status', 'active');
            }])
            ->latest()
            ->get();

        $members = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->get();

        return view('gym-owner.diet-plans.index', compact('plans', 'members'));
    }

    /**
     * Show the form for creating a new diet plan.
     */
    public function create()
    {
        return view('gym-owner.diet-plans.create');
    }

    /**
     * Store a newly created diet plan in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'goal'              => 'required|string|max:100',
            'total_calories'    => 'nullable|integer|min:1',
            'protein_g'         => 'nullable|integer|min:0',
            'carbs_g'           => 'nullable|integer|min:0',
            'fat_g'             => 'nullable|integer|min:0',
            'meals_per_day'     => 'required|integer|min:1|max:10',
            'meals'             => 'required|array',
            'meals.*.meal_name' => 'required|string|max:255',
            'meals.*.time_of_day' => 'nullable|string|max:50',
            'meals.*.food_items'  => 'required|string',
            'meals.*.calories'    => 'nullable|integer|min:0',
            'meals.*.protein_g'   => 'nullable|integer|min:0',
            'meals.*.carbs_g'     => 'nullable|integer|min:0',
            'meals.*.fat_g'       => 'nullable|integer|min:0',
            'meals.*.notes'       => 'nullable|string',
        ], [
            'meals.*.meal_name.required'   => 'Meal name is required for all meals.',
            'meals.*.food_items.required'  => 'Food items are required for all meals.',
        ]);

        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        DB::transaction(function () use ($request, $gymOwnerId, $user) {
            $plan = DietPlan::create([
                'gym_owner_id'  => $gymOwnerId,
                'name'          => $request->name,
                'description'   => $request->description,
                'goal'          => $request->goal,
                'total_calories'=> $request->total_calories,
                'protein_g'     => $request->protein_g,
                'carbs_g'       => $request->carbs_g,
                'fat_g'         => $request->fat_g,
                'meals_per_day' => $request->meals_per_day,
                'created_by'    => $user->id,
            ]);

            foreach ($request->meals as $index => $mealData) {
                DietPlanMeal::create([
                    'diet_plan_id' => $plan->id,
                    'meal_number'  => $index + 1,
                    'meal_name'    => $mealData['meal_name'],
                    'time_of_day'  => $mealData['time_of_day'] ?? null,
                    'food_items'   => $mealData['food_items'],
                    'calories'     => $mealData['calories'] ?? null,
                    'protein_g'    => $mealData['protein_g'] ?? null,
                    'carbs_g'      => $mealData['carbs_g'] ?? null,
                    'fat_g'        => $mealData['fat_g'] ?? null,
                    'notes'        => $mealData['notes'] ?? null,
                    'order'        => $index,
                ]);
            }
        });

        return redirect()->route('gym-owner.diet-plans.index')
            ->with('success', 'Diet plan created successfully.');
    }

    /**
     * Show the form for editing the specified diet plan.
     */
    public function edit(DietPlan $dietPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($dietPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $dietPlan->load('meals');

        return view('gym-owner.diet-plans.edit', compact('dietPlan'));
    }

    /**
     * Update the specified diet plan in storage.
     */
    public function update(Request $request, DietPlan $dietPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($dietPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'goal'              => 'required|string|max:100',
            'total_calories'    => 'nullable|integer|min:1',
            'protein_g'         => 'nullable|integer|min:0',
            'carbs_g'           => 'nullable|integer|min:0',
            'fat_g'             => 'nullable|integer|min:0',
            'meals_per_day'     => 'required|integer|min:1|max:10',
            'meals'             => 'required|array',
            'meals.*.meal_name' => 'required|string|max:255',
            'meals.*.time_of_day' => 'nullable|string|max:50',
            'meals.*.food_items'  => 'required|string',
            'meals.*.calories'    => 'nullable|integer|min:0',
            'meals.*.protein_g'   => 'nullable|integer|min:0',
            'meals.*.carbs_g'     => 'nullable|integer|min:0',
            'meals.*.fat_g'       => 'nullable|integer|min:0',
            'meals.*.notes'       => 'nullable|string',
        ], [
            'meals.*.meal_name.required'   => 'Meal name is required for all meals.',
            'meals.*.food_items.required'  => 'Food items are required for all meals.',
        ]);

        DB::transaction(function () use ($request, $dietPlan) {
            $dietPlan->update([
                'name'          => $request->name,
                'description'   => $request->description,
                'goal'          => $request->goal,
                'total_calories'=> $request->total_calories,
                'protein_g'     => $request->protein_g,
                'carbs_g'       => $request->carbs_g,
                'fat_g'         => $request->fat_g,
                'meals_per_day' => $request->meals_per_day,
            ]);

            // Recreate meals
            $dietPlan->meals()->delete();

            foreach ($request->meals as $index => $mealData) {
                DietPlanMeal::create([
                    'diet_plan_id' => $dietPlan->id,
                    'meal_number'  => $index + 1,
                    'meal_name'    => $mealData['meal_name'],
                    'time_of_day'  => $mealData['time_of_day'] ?? null,
                    'food_items'   => $mealData['food_items'],
                    'calories'     => $mealData['calories'] ?? null,
                    'protein_g'    => $mealData['protein_g'] ?? null,
                    'carbs_g'      => $mealData['carbs_g'] ?? null,
                    'fat_g'        => $mealData['fat_g'] ?? null,
                    'notes'        => $mealData['notes'] ?? null,
                    'order'        => $index,
                ]);
            }
        });

        return redirect()->route('gym-owner.diet-plans.index')
            ->with('success', 'Diet plan updated successfully.');
    }

    /**
     * Remove the specified diet plan from storage.
     */
    public function destroy(DietPlan $dietPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($dietPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $dietPlan->delete();

        return redirect()->route('gym-owner.diet-plans.index')
            ->with('success', 'Diet plan deleted successfully.');
    }

    /**
     * Assign the diet plan to members.
     */
    public function assign(Request $request, DietPlan $dietPlan)
    {
        $user = Auth::user();
        $gymOwnerId = $user->isGymOwner() ? $user->id : $user->gym_owner_id;

        if ($dietPlan->gym_owner_id !== $gymOwnerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'members'   => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $memberIds = $request->input('members', []);

        DB::transaction(function () use ($dietPlan, $memberIds) {
            $currentlyAssigned = DB::table('diet_plan_assignments')
                ->where('diet_plan_id', $dietPlan->id)
                ->where('status', 'active')
                ->pluck('user_id')
                ->toArray();

            // Deactivate removed members
            $toDeactivate = array_diff($currentlyAssigned, $memberIds);
            if (!empty($toDeactivate)) {
                DB::table('diet_plan_assignments')
                    ->where('diet_plan_id', $dietPlan->id)
                    ->whereIn('user_id', $toDeactivate)
                    ->update(['status' => 'inactive', 'updated_at' => now()]);
            }

            // Assign new members (deactivate their previous active diet plans first)
            $toAssign = array_diff($memberIds, $currentlyAssigned);
            if (!empty($toAssign)) {
                DB::table('diet_plan_assignments')
                    ->whereIn('user_id', $toAssign)
                    ->where('status', 'active')
                    ->update(['status' => 'inactive', 'updated_at' => now()]);

                $insertData = [];
                foreach ($toAssign as $memberId) {
                    $insertData[] = [
                        'user_id'     => $memberId,
                        'diet_plan_id'=> $dietPlan->id,
                        'assigned_by' => Auth::id(),
                        'assigned_at' => now(),
                        'status'      => 'active',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
                DB::table('diet_plan_assignments')->insert($insertData);
            }
        });

        return redirect()->route('gym-owner.diet-plans.index')
            ->with('success', 'Diet plan assignments updated successfully.');
    }
}
