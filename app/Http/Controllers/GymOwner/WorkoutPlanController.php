<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    /**
     * Display workout plans management index page.
     */
    public function index()
    {
        return view('gym-owner.workout-plans.index');
    }
}
