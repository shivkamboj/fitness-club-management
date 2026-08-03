@extends('layouts.dashboard')

@section('title', 'My Diet Plan')
@section('page_heading', 'Nutrition & Meal Plan')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">My Assigned Diet & Meal Plan</h2>
        <p class="text-muted mb-0 small">Personalized nutrition plan assigned by your fitness trainer.</p>
    </div>
</div>

@if($dietPlan)
<div class="row g-4">
    <!-- Macro Summary Sidebar -->
    <div class="col-12 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-dark text-success border border-secondary">{{ $dietPlan->goal ?: 'Healthy Living' }}</span>
                <span class="small text-muted font-medium">{{ $dietPlan->meals_per_day }} {{ Str::plural('Meal', $dietPlan->meals_per_day) }} / Day</span>
            </div>

            <h3 class="fw-bold text-white fs-4 mb-2">{{ $dietPlan->name }}</h3>
            <p class="text-muted small mb-4">{{ $dietPlan->description ?: 'Follow this meal routine daily for optimum workout recovery and performance.' }}</p>

            <div class="gwb-widget-box p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold">TOTAL DAILY ENERGY</span>
                    <span class="fw-bold text-warning fs-5"><i class="fa-solid fa-fire me-1"></i>{{ number_format($dietPlan->total_calories) }} kcal</span>
                </div>
                <div class="progress bg-dark" style="height: 8px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"></div>
                </div>
            </div>

            <!-- Macro Targets -->
            <h6 class="fw-bold text-white mb-3">Daily Macronutrient Targets</h6>

            <div class="d-flex flex-column gap-3">
                <div class="gwb-widget-box p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-white font-medium"><i class="fa-solid fa-drumstick-bite text-info me-2"></i>Protein</span>
                        <strong class="text-info fs-5">{{ $dietPlan->protein_g }}g</strong>
                    </div>
                    <div class="progress bg-secondary" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 75%"></div>
                    </div>
                </div>

                <div class="gwb-widget-box p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-white font-medium"><i class="fa-solid fa-bowl-rice text-success me-2"></i>Carbohydrates</span>
                        <strong class="text-success fs-5">{{ $dietPlan->carbs_g }}g</strong>
                    </div>
                    <div class="progress bg-secondary" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 65%"></div>
                    </div>
                </div>

                <div class="gwb-widget-box p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-white font-medium"><i class="fa-solid fa-cheese text-danger me-2"></i>Fats</span>
                        <strong class="text-danger fs-5">{{ $dietPlan->fat_g }}g</strong>
                    </div>
                    <div class="progress bg-secondary" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: 45%"></div>
                    </div>
                </div>
            </div>

            @if($dietPlan->creator)
                <div class="mt-4 pt-3 border-top border-secondary text-muted small d-flex justify-content-between">
                    <span>Created by:</span>
                    <span class="text-white fw-semibold">{{ $dietPlan->creator->full_name }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Meals Breakdown List -->
    <div class="col-12 col-lg-8">
        <div class="gwb-card h-100">
            <h4 class="fw-bold text-white mb-4 fs-5"><i class="fa-solid fa-utensils text-success me-2"></i>Daily Meals Schedule</h4>

            @if($dietPlan->meals && $dietPlan->meals->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($dietPlan->meals as $meal)
                    <div class="gwb-widget-box p-3">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mb-2 gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success text-white px-2 py-1">
                                    {{ $meal->meal_time ?: ('Meal #' . $meal->meal_number) }}
                                </span>
                                <h5 class="fw-bold text-white mb-0">{{ $meal->title }}</h5>
                            </div>
                            <span class="badge bg-dark text-warning border border-secondary align-self-start align-self-sm-auto">
                                <i class="fa-solid fa-fire me-1"></i>{{ $meal->calories }} kcal
                            </span>
                        </div>

                        <p class="text-muted small mb-3">{{ $meal->description ?: 'No specific food notes specified.' }}</p>

                        <div class="d-flex flex-wrap gap-3 small text-muted pt-2 border-top border-secondary opacity-75">
                            @if($meal->protein_g)
                                <span><strong class="text-info">{{ $meal->protein_g }}g</strong> Protein</span>
                            @endif
                            @if($meal->carbs_g)
                                <span><strong class="text-success">{{ $meal->carbs_g }}g</strong> Carbs</span>
                            @endif
                            @if($meal->fat_g)
                                <span><strong class="text-danger">{{ $meal->fat_g }}g</strong> Fat</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-utensils fs-1 mb-3 opacity-50"></i>
                    <h6 class="text-white fw-bold">No Detailed Meals Listed</h6>
                    <p class="small mb-0">Follow your overall daily calorie target specified on the left.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@else
<!-- Empty State -->
<div class="gwb-card text-center py-5 max-w-md mx-auto">
    <i class="fa-solid fa-apple-whole text-muted fs-1 mb-3 opacity-50"></i>
    <h3 class="fw-bold text-white fs-4 mb-2">No Active Diet Plan</h3>
    <p class="text-muted mb-0">You don't currently have an active diet or meal plan assigned. Contact your gym trainer to assign a personalized meal plan.</p>
</div>
@endif
@endsection
