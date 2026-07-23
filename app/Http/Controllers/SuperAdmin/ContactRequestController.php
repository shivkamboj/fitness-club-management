<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    /**
     * Display list of contact form submissions and sales leads.
     */
    public function index()
    {
        return view('super-admin.contacts.index');
    }
}
