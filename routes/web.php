<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, HomeController};



Route::match(['GET', 'POST'], '/', [HomeController::class, 'home'])->name('home');

// Public routes
Route::match(['GET', 'POST'], '/register', [AuthController::class, 'login'])->name('login');
Route::match(['GET', 'POST'], '/register', [AuthController::class, 'register'])->name('register');
Route::match(['GET', 'POST'], '/contact-us', [AuthController::class, 'contact'])->name('contact.store');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::prefix('user')->group(function () {

    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});

