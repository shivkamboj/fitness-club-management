<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display membership plans CRUD page.
     */
    public function index()
    {
        return view('admin.plans.index');
    }
}
