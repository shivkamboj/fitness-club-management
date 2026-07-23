@extends('layouts.dashboard')

@section('title', 'Diet Plans')
@section('page_heading', 'Diet & Nutrition Plans')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Diet & Nutrition Plans</h2>
        <p class="text-muted mb-0 small">Create structured meal plans (timing, food items, calories, macros) and assign to gym members.</p>
    </div>
    <div>
        <button class="btn-gwb-primary">
            <i class="fa-solid fa-plus me-1"></i> Create Diet Plan
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Diet Plan 1 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-dark text-orange border border-secondary">Muscle Gain</span>
                <span class="small text-muted">2,800 kcal</span>
            </div>
            <h3 class="fw-bold text-white fs-4 mb-2">High Protein Lean Bulk</h3>
            <p class="text-muted small">Balanced high-protein meal structure with complex carbs for athletic recovery.</p>
            <div class="p-3 bg-dark rounded border border-secondary mb-3 small">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Macros:</span><span class="text-white">180g P | 320g C | 65g F</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Meals / Day:</span><span class="text-white">5 Meals</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Assigned Members:</span><span class="text-orange fw-bold">19 Members</span></div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-pen me-1"></i> Edit Plan</button>
                <button class="btn btn-gwb-primary w-100"><i class="fa-solid fa-user-plus me-1"></i> Assign</button>
            </div>
        </div>
    </div>

    <!-- Diet Plan 2 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-dark text-orange border border-secondary">Caloric Deficit</span>
                <span class="small text-muted">1,800 kcal</span>
            </div>
            <h3 class="fw-bold text-white fs-4 mb-2">Weight Cut & Shred Diet</h3>
            <p class="text-muted small">Low-calorie, nutrient-dense diet designed for steady weight loss without muscle degradation.</p>
            <div class="p-3 bg-dark rounded border border-secondary mb-3 small">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Macros:</span><span class="text-white">160g P | 140g C | 50g F</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Meals / Day:</span><span class="text-white">4 Meals</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Assigned Members:</span><span class="text-orange fw-bold">31 Members</span></div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-pen me-1"></i> Edit Plan</button>
                <button class="btn btn-gwb-primary w-100"><i class="fa-solid fa-user-plus me-1"></i> Assign</button>
            </div>
        </div>
    </div>
</div>
@endsection
