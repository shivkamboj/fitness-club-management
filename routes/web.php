<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    HomeController,
    MemberController,
    PlanController,
    ClassController,
    TrainerController,
    PaymentController,
    ReportController,
    SettingController
};
use App\Http\Controllers\SuperAdmin\{
    SuperAdminController,
    GymManagementController,
    UserManagementController,
    PlatformSubscriptionController,
    ContactRequestController
};
use App\Http\Controllers\GymOwner\{
    GymOwnerDashboardController,
    WorkoutPlanController,
    DietPlanController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::match(['GET', 'POST'], '/', [HomeController::class, 'home'])->name('home');
Route::get('/website-builder', [HomeController::class, 'websiteBuilder'])->name('website-builder');

// ── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'loginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');

Route::get('/register',  [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/verify-otp',  [AuthController::class, 'verifyOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

Route::get('/forgot-password',  [AuthController::class, 'forgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password',   [AuthController::class, 'resetPasswordForm'])->name('password.reset');
Route::post('/reset-password',  [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ── Contact ──────────────────────────────────────────────────────────────────
Route::match(['GET', 'POST'], '/contact-us', [AuthController::class, 'contact'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Authenticated Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Smart Dashboard Redirect according to Role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }
        return redirect()->route('gym-owner.dashboard');
    })->name('dashboard');

    // ── SUPER ADMIN FLOW (Protected by role:super-admin) ──────────────────────
    Route::middleware('role:super-admin')->prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
        Route::get('/gyms', [GymManagementController::class, 'index'])->name('gyms.index');
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/subscriptions', [PlatformSubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/contacts', [ContactRequestController::class, 'index'])->name('contacts.index');
    });

    // ── GYM OWNER FLOW (Protected by role:gym-owner) ─────────────────────────
    Route::middleware('role:gym-owner')->prefix('gym-owner')->name('gym-owner.')->group(function () {
        Route::get('/dashboard', [GymOwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
        Route::get('/workout-plans', [WorkoutPlanController::class, 'index'])->name('workout-plans.index');
        Route::get('/diet-plans', [DietPlanController::class, 'index'])->name('diet-plans.index');
        Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
        Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    });

});
