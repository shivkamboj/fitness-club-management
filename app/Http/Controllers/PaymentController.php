<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display payments transactions list page.
     */
    public function index()
    {
        return view('admin.payments.index');
    }
}
