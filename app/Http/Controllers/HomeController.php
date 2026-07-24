<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            dd('koko');
        }

        return view('gym-management');
    }

    public function websiteBuilder()
    {
        return view('landing');
    }
}
