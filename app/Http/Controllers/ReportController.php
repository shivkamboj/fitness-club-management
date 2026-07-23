<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display reports and analytics overview page.
     */
    public function index()
    {
        return view('admin.reports.index');
    }
}
