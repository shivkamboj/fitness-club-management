@extends('layouts.dashboard')

@section('title', 'Trainer Profiles')
@section('page_heading', 'Trainers & Instructors')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Trainers Directory</h2>
        <p class="text-muted mb-0 small">Manage trainer profiles, specializations, hourly rates, and working hours.</p>
    </div>
    <div>
        <button class="btn-gwb-primary">
            <i class="fa-solid fa-user-plus me-1"></i> Add Trainer
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Trainer Card 1 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100 text-center">
            <div class="user-avatar mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.5rem;">RK</div>
            <h3 class="fw-bold text-white fs-4 mb-1">Rahul Kapoor</h3>
            <span class="badge bg-dark text-orange border border-secondary mb-3">Head Strength Coach</span>
            <p class="text-muted small">Specializes in Powerlifting, Bodybuilding, and Hypertrophy training.</p>
            <div class="p-2 rounded bg-dark border border-secondary mb-3 text-start small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Rate:</span>
                    <span class="text-white fw-bold">₹1,500 / hr</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Active Clients:</span>
                    <span class="text-orange fw-bold">14 Members</span>
                </div>
            </div>
            <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-user-gear me-1"></i> Manage Availability</button>
        </div>
    </div>

    <!-- Trainer Card 2 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100 text-center">
            <div class="user-avatar mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.5rem;">ME</div>
            <h3 class="fw-bold text-white fs-4 mb-1">Maya Mehta</h3>
            <span class="badge bg-dark text-orange border border-secondary mb-3">Flexibility & Functional Coach</span>
            <p class="text-muted small">Certified Yoga Master & Functional Movement Specialist.</p>
            <div class="p-2 rounded bg-dark border border-secondary mb-3 text-start small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Rate:</span>
                    <span class="text-white fw-bold">₹1,200 / hr</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Active Clients:</span>
                    <span class="text-orange fw-bold">9 Members</span>
                </div>
            </div>
            <button class="btn btn-gwb-secondary w-100"><i class="fa-solid fa-user-gear me-1"></i> Manage Availability</button>
        </div>
    </div>
</div>
@endsection
