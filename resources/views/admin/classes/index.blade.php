@extends('layouts.dashboard')

@section('title', 'Classes & Schedules')
@section('page_heading', 'Classes & Schedules')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Group Classes & Schedules</h2>
        <p class="text-muted mb-0 small">Manage gym group sessions, assigned trainers, and seat availability.</p>
    </div>
    <div>
        <button class="btn-gwb-primary">
            <i class="fa-solid fa-calendar-plus me-1"></i> Add New Class
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Class Item 1 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-dark text-orange border border-secondary">CrossFit</span>
                <span class="small text-muted"><i class="fa-regular fa-clock me-1"></i> 60 mins</span>
            </div>
            <h3 class="fw-bold text-white fs-4 mb-2">High-Intensity HIIT</h3>
            <p class="text-muted small">Full body conditioning with kettlebells, ropes, and rowers.</p>
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="user-avatar" style="width: 28px; height: 28px;">RK</div>
                <span class="small text-white fw-semibold">Trainer: Rahul Kapoor</span>
            </div>
            <div class="p-2 rounded bg-dark border border-secondary mb-3">
                <div class="d-flex justify-content-between small text-muted">
                    <span>Capacity</span>
                    <span class="text-orange fw-bold">18 / 20 Booked</span>
                </div>
                <div class="progress mt-1" style="height: 6px; background-color: var(--gwb-surface-2);">
                    <div class="progress-bar bg-orange" style="width: 90%;"></div>
                </div>
            </div>
            <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-users me-1"></i> View Roster</button>
        </div>
    </div>

    <!-- Class Item 2 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-dark text-orange border border-secondary">Yoga</span>
                <span class="small text-muted"><i class="fa-regular fa-clock me-1"></i> 45 mins</span>
            </div>
            <h3 class="fw-bold text-white fs-4 mb-2">Vinyasa Power Yoga</h3>
            <p class="text-muted small">Breathwork, flexibility, and core strength flow class.</p>
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="user-avatar" style="width: 28px; height: 28px;">ME</div>
                <span class="small text-white fw-semibold">Trainer: Maya Mehta</span>
            </div>
            <div class="p-2 rounded bg-dark border border-secondary mb-3">
                <div class="d-flex justify-content-between small text-muted">
                    <span>Capacity</span>
                    <span class="text-orange fw-bold">12 / 15 Booked</span>
                </div>
                <div class="progress mt-1" style="height: 6px; background-color: var(--gwb-surface-2);">
                    <div class="progress-bar bg-orange" style="width: 80%;"></div>
                </div>
            </div>
            <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-users me-1"></i> View Roster</button>
        </div>
    </div>
</div>
@endsection
