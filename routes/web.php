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
    SettingController,
    SocialAuthController
};
use App\Services\SocialAuthService;
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
    DietPlanController,
    GroupClassController,
    GymSettingController
};
use App\Http\Controllers\Trainer\TrainerDashboardController;

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

// ── Social Login (authentication only — never creates users) ─────────────────
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
    ->whereIn('provider', SocialAuthService::SUPPORTED_PROVIDERS)
    ->name('social.redirect');

Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->whereIn('provider', SocialAuthService::SUPPORTED_PROVIDERS)
    ->name('social.callback');

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

        if ($user && method_exists($user, 'isTrainer') && $user->isTrainer()) {
            return redirect()->route('trainer.dashboard');
        }

        if ($user && method_exists($user, 'isGymOwner') && $user->isGymOwner()) {
            return redirect()->route('gym-owner.dashboard');
        }

        if ($user && ((int)$user->role === 0 || (int)$user->role === 5)) {
            return redirect()->route('member.workouts');
        }

        return redirect()->route('login')->with('error', 'Access Denied.');
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
        Route::post('/trainers', [TrainerController::class, 'store'])->name('trainers.store');
        Route::get('/trainers/generate-password', [TrainerController::class, 'generatePassword'])->name('trainers.generate-password');
        Route::get('/trainers/{trainer}', [TrainerController::class, 'show'])->name('trainers.show');
        Route::match(['put', 'post'], '/trainers/{trainer}', [TrainerController::class, 'update'])->name('trainers.update');
        Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
        Route::patch('/trainers/{trainer}/status', [TrainerController::class, 'updateStatus'])->name('trainers.status');

        Route::get('/workout-plans', [WorkoutPlanController::class, 'index'])->name('workout-plans.index');
        Route::get('/workout-plans/create', [WorkoutPlanController::class, 'create'])->name('workout-plans.create');
        Route::post('/workout-plans', [WorkoutPlanController::class, 'store'])->name('workout-plans.store');
        Route::get('/workout-plans/{workoutPlan}/edit', [WorkoutPlanController::class, 'edit'])->name('workout-plans.edit');
        Route::put('/workout-plans/{workoutPlan}', [WorkoutPlanController::class, 'update'])->name('workout-plans.update');
        Route::delete('/workout-plans/{workoutPlan}', [WorkoutPlanController::class, 'destroy'])->name('workout-plans.destroy');
        Route::post('/workout-plans/{workoutPlan}/assign', [WorkoutPlanController::class, 'assign'])->name('workout-plans.assign');

        Route::get('/diet-plans', [DietPlanController::class, 'index'])->name('diet-plans.index');
        Route::get('/diet-plans/create', [DietPlanController::class, 'create'])->name('diet-plans.create');
        Route::post('/diet-plans', [DietPlanController::class, 'store'])->name('diet-plans.store');
        Route::get('/diet-plans/{dietPlan}/edit', [DietPlanController::class, 'edit'])->name('diet-plans.edit');
        Route::put('/diet-plans/{dietPlan}', [DietPlanController::class, 'update'])->name('diet-plans.update');
        Route::delete('/diet-plans/{dietPlan}', [DietPlanController::class, 'destroy'])->name('diet-plans.destroy');
        Route::post('/diet-plans/{dietPlan}/assign', [DietPlanController::class, 'assign'])->name('diet-plans.assign');

        // Group Classes & Schedules
        Route::get('/classes', [GroupClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/create', [GroupClassController::class, 'create'])->name('classes.create');
        Route::post('/classes', [GroupClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/{class}/edit', [GroupClassController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{class}', [GroupClassController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{class}', [GroupClassController::class, 'destroy'])->name('classes.destroy');
        Route::get('/classes/{class}/roster', [GroupClassController::class, 'roster'])->name('classes.roster');
        Route::post('/classes/{class}/roster/add', [GroupClassController::class, 'addMember'])->name('classes.roster.add');
        Route::delete('/classes/bookings/{booking}', [GroupClassController::class, 'removeMember'])->name('classes.roster.remove');
        Route::patch('/classes/bookings/{booking}/status', [GroupClassController::class, 'updateBookingStatus'])->name('classes.booking.status');

        Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        // Gym Settings
        Route::get('/settings', [GymSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/profile', [GymSettingController::class, 'updateProfile'])->name('settings.profile');
        Route::post('/settings/logo', [GymSettingController::class, 'updateLogo'])->name('settings.logo');
        Route::delete('/settings/logo', [GymSettingController::class, 'removeLogo'])->name('settings.logo.remove');
        Route::post('/settings/taxes', [GymSettingController::class, 'updateTaxes'])->name('settings.taxes');
        Route::post('/settings/currency', [GymSettingController::class, 'updateCurrency'])->name('settings.currency');
        Route::post('/settings/hours', [GymSettingController::class, 'updateWorkingHours'])->name('settings.hours');
        Route::post('/settings/branches', [GymSettingController::class, 'storeBranch'])->name('settings.branches.store');
        Route::put('/settings/branches/{branch}', [GymSettingController::class, 'updateBranch'])->name('settings.branches.update');
        Route::delete('/settings/branches/{branch}', [GymSettingController::class, 'destroyBranch'])->name('settings.branches.destroy');
        Route::post('/settings/sms', [GymSettingController::class, 'updateSms'])->name('settings.sms');
        Route::post('/settings/whatsapp', [GymSettingController::class, 'updateWhatsapp'])->name('settings.whatsapp');
        Route::get('/settings/backup', [GymSettingController::class, 'downloadBackup'])->name('settings.backup');
    });

    // ── TRAINER FLOW (Protected by role:trainer) ─────────────────────────────
    Route::middleware('role:trainer')->prefix('trainer')->name('trainer.')->group(function () {
        Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
    });

    // ── MEMBER FLOW ──────────────────────────────────────────────────────────
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/workouts', [WorkoutPlanController::class, 'memberWorkouts'])->name('workouts');
        Route::post('/workouts/toggle-complete', [WorkoutPlanController::class, 'toggleCompleteExercise'])->name('workouts.toggle-complete');
    });

});
