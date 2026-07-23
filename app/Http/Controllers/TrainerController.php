<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Display trainers list and availability page.
     */
    public function index()
    {
        return view('admin.trainers.index');
    }
}
