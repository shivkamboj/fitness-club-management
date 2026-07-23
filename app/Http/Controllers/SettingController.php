<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings and gym profile management page.
     */
    public function index()
    {
        return view('admin.settings.index');
    }
}
