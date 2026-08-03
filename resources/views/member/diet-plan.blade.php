@extends('layouts.dashboard')

@section('title', 'My Diet Plan')
@section('page_heading', 'Nutrition & Meal Plan')

@section('content')
@push('styles')
<style>
    .diet-hero-card {
        background: var(--gwb-surface-2, #1e1e28);
        border: 1px solid var(--gwb-border, #2a2a35);
        border-radius: 14px;
        padding: 1.25rem;
    }
    [data-theme="light"] .diet-hero-card {
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .diet-goal-pill {
        background: linear-gradient(135deg, var(--gwb-orange, #ff5a1f), #d84307);
        color: #ffffff;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    .energy-widget-box {
        background: var(--gwb-surface-1, #14141c);
        border: 1px solid var(--gwb-border, #2a2a35);
        border-radius: 12px;
        padding: 1rem;
    }
    [data-theme="light"] .energy-widget-box {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .macro-progress-box {
        background: var(--gwb-surface-1, #14141c);
        border: 1px solid var(--gwb-border, #2a2a35);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        transition: transform 0.2s ease;
    }
    [data-theme="light"] .macro-progress-box {
        background: #ffffff;
        border-color: #e2e8f0;
    }
    .macro-progress-box:hover {
        transform: translateY(-2px);
    }
    .meal-card-item {
        background: var(--gwb-surface-1, #14141c);
        border: 1px solid var(--gwb-border, #2a2a35);
        border-radius: 12px;
        padding: 1.15rem;
        transition: all 0.25s ease;
    }
    [data-theme="light"] .meal-card-item {
        background: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }
    .meal-card-item:hover {
        border-color: var(--gwb-orange, #ff5a1f);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }
    .meal-pill-tag {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
    }
    .meal-calorie-tag {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.25);
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.825rem;
    }
    [data-theme="dark"] .meal-calorie-tag {
        background: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
        border-color: rgba(245, 158, 11, 0.3);
    }
    .meal-macro-tag {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .meal-macro-tag.macro-protein {
        background-color: rgba(6, 182, 212, 0.1);
        color: #0891b2;
        border: 1px solid rgba(6, 182, 212, 0.25);
    }
    .meal-macro-tag.macro-carbs {
        background-color: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }
    .meal-macro-tag.macro-fat {
        background-color: rgba(244, 63, 94, 0.1);
        color: #e11d48;
        border: 1px solid rgba(244, 63, 94, 0.25);
    }
    [data-theme="dark"] .meal-macro-tag.macro-protein {
        background-color: rgba(6, 182, 212, 0.15);
        color: #22d3ee;
        border-color: rgba(6, 182, 212, 0.3);
    }
    [data-theme="dark"] .meal-macro-tag.macro-carbs {
        background-color: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border-color: rgba(16, 185, 129, 0.3);
    }
    [data-theme="dark"] .meal-macro-tag.macro-fat {
        background-color: rgba(244, 63, 94, 0.15);
        color: #fb7185;
        border-color: rgba(244, 63, 94, 0.3);
    }
</style>
@endpush

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">My Assigned Diet & Meal Plan</h2>
        <p class="text-muted mb-0 small">Personalized nutrition plan assigned by your fitness trainer.</p>
    </div>
</div>

@if($dietPlan)
<div class="row g-4">
    <!-- Macro Summary Sidebar Column -->
    <div class="col-12 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="diet-goal-pill"><i class="fa-solid fa-bullseye me-1"></i>{{ $dietPlan->goal ?: 'Healthy Living' }}</span>
                <span class="small text-muted font-medium"><i class="fa-solid fa-utensils text-orange me-1"></i>{{ $dietPlan->meals_per_day }} {{ Str::plural('Meal', $dietPlan->meals_per_day) }} / Day</span>
            </div>

            <h3 class="fw-bold text-white fs-4 mb-2">{{ $dietPlan->name }}</h3>
            <p class="text-muted small mb-4">{{ $dietPlan->description ?: 'Follow this meal routine daily for optimum workout recovery and performance.' }}</p>

            <div class="energy-widget-box mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold tracking-wider">TOTAL DAILY ENERGY</span>
                    <span class="fw-bold text-warning fs-5"><i class="fa-solid fa-fire me-1 text-danger"></i>{{ number_format($dietPlan->total_calories) }} kcal</span>
                </div>
                <div class="progress bg-secondary" style="height: 8px; border-radius: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: 100%; background: linear-gradient(90deg, #f59e0b, #ff5a1f);"></div>
                </div>
            </div>

            <!-- Daily Macro Targets Breakdown -->
            <h6 class="fw-bold text-white mb-3"><i class="fa-solid fa-chart-pie text-orange me-2"></i>Daily Macronutrient Targets</h6>

            <div class="d-flex flex-column gap-3">
                <div class="macro-progress-box">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-white fw-semibold"><i class="fa-solid fa-drumstick-bite text-info me-2"></i>Protein</span>
                        <strong class="text-info fs-5">{{ $dietPlan->protein_g }}g</strong>
                    </div>
                    <div class="progress bg-secondary" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar" style="width: 75%; background: linear-gradient(90deg, #06b6d4, #3b82f6);"></div>
                    </div>
                </div>

                <div class="macro-progress-box">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-white fw-semibold"><i class="fa-solid fa-bowl-rice text-success me-2"></i>Carbohydrates</span>
                        <strong class="text-success fs-5">{{ $dietPlan->carbs_g }}g</strong>
                    </div>
                    <div class="progress bg-secondary" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar" style="width: 65%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                    </div>
                </div>

                <div class="macro-progress-box">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-white fw-semibold"><i class="fa-solid fa-cheese text-danger me-2"></i>Fats</span>
                        <strong class="text-danger fs-5">{{ $dietPlan->fat_g }}g</strong>
                    </div>
                    <div class="progress bg-secondary" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar" style="width: 45%; background: linear-gradient(90deg, #f43f5e, #e11d48);"></div>
                    </div>
                </div>
            </div>

            @if($dietPlan->creator)
                <div class="mt-4 pt-3 border-top border-secondary text-muted small d-flex justify-content-between align-items-center">
                    <span>Assigned By:</span>
                    <span class="text-white fw-semibold"><i class="fa-solid fa-user-ninja text-warning me-1"></i>{{ $dietPlan->creator->full_name }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Meals Schedule Breakdown List Column -->
    <div class="col-12 col-lg-8">
        <div class="gwb-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold text-white mb-0 fs-5">
                    <i class="fa-solid fa-utensils text-success me-2"></i>Daily Meals Schedule
                </h4>
                <span class="badge bg-dark text-muted border border-secondary">{{ $dietPlan->meals->count() }} Meals Planned</span>
            </div>

            @if($dietPlan->meals && $dietPlan->meals->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($dietPlan->meals as $meal)
                    <div class="meal-card-item">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mb-2.5 gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="meal-pill-tag">
                                    {{ $meal->meal_time ?: ('Meal #' . $meal->meal_number) }}
                                </span>
                                <h5 class="fw-bold text-white mb-0 fs-5">{{ $meal->title }}</h5>
                            </div>
                            <span class="meal-calorie-tag align-self-start align-self-sm-auto">
                                <i class="fa-solid fa-fire me-1"></i>{{ $meal->calories }} kcal
                            </span>
                        </div>

                        <p class="text-muted small mb-3 leading-relaxed">{{ $meal->description ?: 'No specific food notes specified.' }}</p>

                        <div class="d-flex flex-wrap gap-2 pt-2 border-top border-secondary opacity-90">
                            @if($meal->protein_g)
                                <span class="meal-macro-tag macro-protein">
                                    <i class="fa-solid fa-drumstick-bite"></i>
                                    <strong>{{ $meal->protein_g }}g</strong> Protein
                                </span>
                            @endif
                            @if($meal->carbs_g)
                                <span class="meal-macro-tag macro-carbs">
                                    <i class="fa-solid fa-bowl-rice"></i>
                                    <strong>{{ $meal->carbs_g }}g</strong> Carbs
                                </span>
                            @endif
                            @if($meal->fat_g)
                                <span class="meal-macro-tag macro-fat">
                                    <i class="fa-solid fa-cheese"></i>
                                    <strong>{{ $meal->fat_g }}g</strong> Fat
                                </span>
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
