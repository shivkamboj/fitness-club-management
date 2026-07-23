<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlatformSubscriptionController extends Controller
{
    /**
     * Display SaaS subscription plans purchased by gym owners.
     */
    public function index()
    {
        return view('super-admin.subscriptions.index');
    }
}
