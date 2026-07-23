<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GymManagementController extends Controller
{
    /**
     * Display list of gym owners and registered gyms.
     */
    public function index()
    {
        return view('super-admin.gyms.index');
    }
}
