<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display class schedules and booking page.
     */
    public function index()
    {
        return view('admin.classes.index');
    }
}
