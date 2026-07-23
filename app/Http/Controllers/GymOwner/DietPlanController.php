<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DietPlanController extends Controller
{
    /**
     * Display diet plans management index page.
     */
    public function index()
    {
        return view('gym-owner.diet-plans.index');
    }
}
