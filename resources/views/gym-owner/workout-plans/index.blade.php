@extends('layouts.dashboard')

@section('title', 'Workout Plans')
@section('page_heading', 'Workout Plans Management')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Workout Plans & Routines</h2>
        <p class="text-muted mb-0 small">Create customized exercise routines (sets, reps, rest periods) and assign to members.</p>
    </div>
    <div>
        <button class="btn-gwb-primary">
            <i class="fa-solid fa-plus me-1"></i> Create Workout Plan
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Workout Plan 1 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-dark text-orange border border-secondary">Hypertrophy</span>
                <span class="small text-muted">4 Days / Week</span>
            </div>
            <h3 class="fw-bold text-white fs-4 mb-2">4-Day Upper/Lower Split</h3>
            <p class="text-muted small">Designed for muscle building and progressive overload strength gain.</p>
            <div class="p-3 bg-dark rounded border border-secondary mb-3 small">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Exercises:</span><span class="text-white">16 Total</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Assigned Members:</span><span class="text-orange fw-bold">24 Members</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Created By:</span><span class="text-white">Rahul Kapoor</span></div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-pen me-1"></i> Edit Plan</button>
                <button class="btn btn-gwb-primary w-100"><i class="fa-solid fa-user-plus me-1"></i> Assign</button>
            </div>
        </div>
    </div>

    <!-- Workout Plan 2 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-dark text-orange border border-secondary">Fat Loss</span>
                <span class="small text-muted">5 Days / Week</span>
            </div>
            <h3 class="fw-bold text-white fs-4 mb-2">Shred & Lean Metabolic Circuit</h3>
            <p class="text-muted small">High heart rate metabolic conditioning mixed with dumbbell circuits.</p>
            <div class="p-3 bg-dark rounded border border-secondary mb-3 small">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Exercises:</span><span class="text-white">20 Total</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Assigned Members:</span><span class="text-orange fw-bold">38 Members</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Created By:</span><span class="text-white">Maya Mehta</span></div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-pen me-1"></i> Edit Plan</button>
                <button class="btn btn-gwb-primary w-100"><i class="fa-solid fa-user-plus me-1"></i> Assign</button>
            </div>
        </div>
    </div>
</div>
@endsection
