<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display members list page.
     */
    public function index()
    {
        return view('admin.members.index');
    }
}
