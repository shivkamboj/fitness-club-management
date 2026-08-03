@extends('layouts.dashboard')

@section('title', 'Member Dashboard')
@section('page_heading', 'Member Overview')

@section('content')
<!-- Member Welcome Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Welcome back, {{ $member->first_name ?: $member->name }}!</h2>
        <p class="text-muted mb-0 small">
            <i class="fa-solid fa-building text-orange me-1"></i> Member at <strong class="text-white">{{ $gymName }}</strong>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('member.workouts') }}" class="btn-gwb-primary">
            <i class="fa-solid fa-dumbbell me-1"></i> Start Workout
        </a>
        <a href="{{ route('member.classes') }}" class="btn-gwb-secondary">
            <i class="fa-solid fa-calendar-plus me-1"></i> Book Class
        </a>
    </div>
</div>

<!-- 4 Key Status Cards -->
<div class="row g-3 mb-4">
    <!-- Active Workout Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small text-uppercase fw-semibold tracking-wider">Workout Routine</span>
                <div class="rounded-3 p-2 bg-dark text-orange border border-secondary">
                    <i class="fa-solid fa-dumbbell fs-5"></i>
                </div>
            </div>
            @if($activeWorkoutPlan)
                <h4 class="fw-bold text-white mb-1 text-truncate" title="{{ $activeWorkoutPlan->name }}">{{ $activeWorkoutPlan->name }}</h4>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="badge bg-dark text-orange border border-secondary">{{ $activeWorkoutPlan->goal }}</span>
                    <span class="small text-muted">{{ $todayCompletedExercisesCount }}/{{ $activeWorkoutPlan->exercises->count() }} done today</span>
                </div>
            @else
                <h5 class="fw-semibold text-muted mb-1">No Active Plan</h5>
                <span class="small text-muted">Ask trainer for workout split</span>
            @endif
        </div>
    </div>

    <!-- Active Diet Plan Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small text-uppercase fw-semibold tracking-wider">Diet & Nutrition</span>
                <div class="rounded-3 p-2 bg-dark text-success border border-secondary">
                    <i class="fa-solid fa-apple-whole fs-5"></i>
                </div>
            </div>
            @if($activeDietPlan)
                <h4 class="fw-bold text-white mb-1 text-truncate" title="{{ $activeDietPlan->name }}">{{ $activeDietPlan->name }}</h4>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="badge bg-dark text-success border border-secondary">{{ $activeDietPlan->goal ?? 'Healthy Living' }}</span>
                    <span class="small text-white fw-semibold"><i class="fa-solid fa-fire me-1 text-warning"></i>{{ number_format($activeDietPlan->total_calories) }} kcal</span>
                </div>
            @else
                <h5 class="fw-semibold text-muted mb-1">No Diet Plan</h5>
                <span class="small text-muted">Ask trainer for meal plan</span>
            @endif
        </div>
    </div>

    <!-- Class Bookings Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small text-uppercase fw-semibold tracking-wider">Group Classes</span>
                <div class="rounded-3 p-2 bg-dark text-info border border-secondary">
                    <i class="fa-solid fa-calendar-check fs-5"></i>
                </div>
            </div>
            <h4 class="fw-bold text-white mb-1">{{ $upcomingBookings->count() }} Active {{ Str::plural('Booking', $upcomingBookings->count()) }}</h4>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <span class="small text-muted">Reserved seats</span>
                <a href="{{ route('member.classes') }}" class="small text-info text-decoration-none">View all <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Membership Status Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small text-uppercase fw-semibold tracking-wider">Membership</span>
                <div class="rounded-3 p-2 bg-dark text-warning border border-secondary">
                    <i class="fa-solid fa-id-card fs-5"></i>
                </div>
            </div>
            <h4 class="fw-bold text-white mb-1 text-truncate">{{ $membershipPlan ? $membershipPlan->name : 'Standard Membership' }}</h4>
            <div class="d-flex align-items-center justify-content-between mt-2">
                @if($daysRemaining !== null)
                    @if($daysRemaining > 7)
                        <span class="badge bg-success text-white">{{ $daysRemaining }} days left</span>
                    @elseif($daysRemaining >= 0)
                        <span class="badge bg-warning text-dark">{{ $daysRemaining }} days left (Renew Soon)</span>
                    @else
                        <span class="badge bg-danger text-white">Expired</span>
                    @endif
                @else
                    <span class="badge bg-success text-white">Active</span>
                @endif
                <span class="small text-muted">{{ $expiresAt ? $expiresAt->format('M d, Y') : 'Ongoing' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Detailed Content Layout -->
<div class="row g-4">
    <!-- Left Column: Workout Routine & Diet Summary -->
    <div class="col-12 col-lg-8">
        <!-- Assigned Workout Routine Overview -->
        <div class="gwb-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-dumbbell text-orange fs-4"></i>
                    <div>
                        <h4 class="fw-bold text-white mb-0">Assigned Workout Routine</h4>
                        <span class="small text-muted">Daily exercises assigned by your trainer</span>
                    </div>
                </div>
                <a href="{{ route('member.workouts') }}" class="btn-gwb-primary btn-sm">
                    <i class="fa-solid fa-arrow-right me-1"></i> Open Full Workout Split
                </a>
            </div>

            @if($activeWorkoutPlan && $activeWorkoutPlan->exercises->count() > 0)
                <div class="p-3 bg-secondary rounded mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-bold text-white fs-5">{{ $activeWorkoutPlan->name }}</span>
                            <span class="badge bg-dark text-orange border border-secondary ms-2">{{ $activeWorkoutPlan->goal }}</span>
                        </div>
                        <span class="small text-muted">{{ $activeWorkoutPlan->days_per_week }} Days / Week</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="gwb-table align-middle">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Exercise</th>
                                <th class="text-center">Sets × Reps</th>
                                <th class="text-center">Rest</th>
                                <th class="text-end">Guide</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeWorkoutPlan->exercises->take(6) as $exercise)
                            <tr>
                                <td><span class="badge bg-dark text-white border border-secondary">Day {{ $exercise->day_number }}</span></td>
                                <td class="fw-semibold text-white">{{ $exercise->exercise_name }}</td>
                                <td class="text-center"><span class="text-orange fw-bold">{{ $exercise->sets }}</span> × {{ $exercise->reps }}</td>
                                <td class="text-center text-muted small">{{ $exercise->rest ?: '60s' }}</td>
                                <td class="text-end">
                                    @if($exercise->video_link)
                                        <a href="{{ $exercise->video_link }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                            <i class="fa-solid fa-circle-play"></i>
                                        </a>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-dumbbell fs-1 mb-2 opacity-50"></i>
                    <h6 class="text-white fw-bold">No Active Workout Plan Assigned</h6>
                    <p class="small mb-0">Ask your trainer to create and assign a tailored workout split for you.</p>
                </div>
            @endif
        </div>

        <!-- Assigned Diet & Nutrition Overview -->
        <div class="gwb-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-apple-whole text-success fs-4"></i>
                    <div>
                        <h4 class="fw-bold text-white mb-0">Assigned Diet & Meals</h4>
                        <span class="small text-muted">Nutrition goals and daily meal plan</span>
                    </div>
                </div>
                <a href="{{ route('member.diet-plan') }}" class="btn-gwb-secondary btn-sm">
                    <i class="fa-solid fa-utensils me-1"></i> View Meal Breakdown
                </a>
            </div>

            @if($activeDietPlan)
                <div class="row g-2 mb-3">
                    <div class="col-6 col-sm-3">
                        <div class="p-2 bg-secondary rounded text-center">
                            <span class="small text-muted d-block">Calories</span>
                            <strong class="text-warning fs-5">{{ number_format($activeDietPlan->total_calories) }}</strong> <span class="small text-muted">kcal</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 bg-secondary rounded text-center">
                            <span class="small text-muted d-block">Protein</span>
                            <strong class="text-info fs-5">{{ $activeDietPlan->protein_g }}</strong> <span class="small text-muted">g</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 bg-secondary rounded text-center">
                            <span class="small text-muted d-block">Carbs</span>
                            <strong class="text-success fs-5">{{ $activeDietPlan->carbs_g }}</strong> <span class="small text-muted">g</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 bg-secondary rounded text-center">
                            <span class="small text-muted d-block">Fat</span>
                            <strong class="text-danger fs-5">{{ $activeDietPlan->fat_g }}</strong> <span class="small text-muted">g</span>
                        </div>
                    </div>
                </div>

                @if($activeDietPlan->meals && $activeDietPlan->meals->count() > 0)
                    <div class="list-group list-group-flush bg-transparent">
                        @foreach($activeDietPlan->meals->take(4) as $meal)
                        <div class="list-group-item bg-transparent text-white border-secondary px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-dark text-success border border-secondary me-2">{{ $meal->meal_time ?: ('Meal ' . $meal->meal_number) }}</span>
                                <strong class="text-white">{{ $meal->title }}</strong>
                                <span class="small text-muted d-block ms-1">{{ $meal->description }}</span>
                            </div>
                            <span class="badge bg-dark text-warning border border-secondary">{{ $meal->calories }} kcal</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-apple-whole fs-1 mb-2 opacity-50"></i>
                    <h6 class="text-white fw-bold">No Diet Plan Assigned</h6>
                    <p class="small mb-0">Ask your trainer to prepare a custom meal plan for your fitness goals.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Upcoming Group Classes & Gym Info -->
    <div class="col-12 col-lg-4">
        <!-- Reserved Group Classes Card -->
        <div class="gwb-card mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold text-white mb-0 fs-5"><i class="fa-solid fa-calendar-day text-info me-2"></i>My Booked Classes</h4>
                <a href="{{ route('member.classes') }}" class="small text-info text-decoration-none">Manage</a>
            </div>

            @forelse($upcomingBookings as $booking)
                <div class="p-3 bg-secondary rounded mb-2 border border-secondary">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="fw-bold text-white mb-0">{{ $booking->groupClass?->name }}</h6>
                        <span class="badge bg-success">Booked</span>
                    </div>
                    <div class="small text-muted">
                        <i class="fa-solid fa-clock me-1 text-orange"></i>{{ $booking->groupClass?->start_time ?: 'Scheduled' }} ({{ $booking->groupClass?->duration_minutes }} mins)
                    </div>
                    @if($booking->groupClass?->trainer)
                        <div class="small text-muted mt-1">
                            <i class="fa-solid fa-user-ninja me-1 text-warning"></i>Trainer: {{ $booking->groupClass->trainer->full_name }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-3 text-muted">
                    <i class="fa-regular fa-calendar-xmark fs-3 mb-2"></i>
                    <p class="small mb-2">No class seats reserved currently.</p>
                    <a href="{{ route('member.classes') }}" class="btn-gwb-primary btn-sm">
                        <i class="fa-solid fa-calendar-plus me-1"></i> Browse Classes
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Gym Center & Support Card -->
        <div class="gwb-card">
            <h4 class="fw-bold text-white mb-3 fs-5"><i class="fa-solid fa-building-circle-check text-orange me-2"></i>My Gym Center</h4>
            <div class="p-3 bg-secondary rounded mb-3">
                <h5 class="fw-bold text-white mb-1">{{ $gymName }}</h5>
                <p class="small text-muted mb-0"><i class="fa-solid fa-user-shield me-1"></i>Owner: {{ $member->gymOwner?->full_name ?: 'Gym Management' }}</p>
                <p class="small text-muted mb-0"><i class="fa-regular fa-envelope me-1"></i>{{ $member->gymOwner?->email ?: 'support@gym.com' }}</p>
            </div>

            <div class="p-3 border border-secondary rounded">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fa-solid fa-heart-pulse text-danger fs-5"></i>
                    <h6 class="fw-bold text-white mb-0">Stay Consistent!</h6>
                </div>
                <p class="small text-muted mb-0">Complete your daily exercise routines, stick to your calorie intake, and attend group classes to reach your targets fast.</p>
            </div>
        </div>
    </div>
</div>
@endsection
