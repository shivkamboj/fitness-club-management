<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register (Request $request) {

        if ($request->getMethod() === 'POST') {
            dd('koko');
        }

        return view('auth.register');
    }

    public function login(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            dd('koko');
        }

        return view('auth.login');
    }

    public function contact(Request $request)
    {
        if ($request->getMethod() === 'POST') {
             $validated = $request->validate([
                'name'    => 'required|string|max:255',
                'gym_name'=> 'nullable|string|max:255',
                'email'   => 'required|email|max:255',
                'phone'   => 'required|string|max:30',
                'message' => 'nullable|string|max:2000',
            ]);

            // TODO: store lead in DB, send email/notification, etc.

            return back()->with('success', 'Thanks! We will get back to you within 24 hours.');
        }

        return view('auth.login');
    }
}
