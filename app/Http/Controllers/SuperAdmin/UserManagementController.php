<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display overall user directory across all gyms.
     */
    public function index()
    {
        return view('super-admin.users.index');
    }
}
