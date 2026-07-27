@extends('layouts.dashboard')

@section('title', 'Membership Plans')
@section('page_heading', 'Membership Plans')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Membership Plans & Pricing</h2>
        <p class="text-muted mb-0 small">Create, edit, and configure subscription tier packages for your members.</p>
    </div>
    <div>
        <button class="btn-gwb-primary">
            <i class="fa-solid fa-plus me-1"></i> Create New Plan
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Plan Card 1 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100 position-relative border-orange">
            <span class="position-absolute top-0 end-0 m-3 badge bg-danger">Popular</span>
            <h3 class="fw-bold text-white fs-4 mb-2">Monthly Elite</h3>
            <div class="fw-bold fs-2 text-orange mb-3">₹2,499 <span class="fs-6 text-muted font-body fw-normal">/ month</span></div>
            <p class="text-muted small">Full gym access, locker facilities, and 2 group classes per week.</p>
            <hr class="border-secondary opacity-25">
            <ul class="list-unstyled  small d-flex flex-column gap-2 mb-4">
                <li><i class="fa-solid fa-check text-orange me-2"></i> 24/7 Gym Floor Access</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> Free Locker Room & Steam</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> 2 Group Classes Included</li>
                <li class="text-muted"><i class="fa-solid fa-xmark me-2"></i> Personal Trainer Session</li>
            </ul>
            <div class="mt-auto d-flex gap-2">
                <button class="btn btn-gwb-secondary w-100 py-2"><i class="fa-solid fa-pen me-1"></i> Edit Plan</button>
            </div>
        </div>
    </div>

    <!-- Plan Card 2 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <h3 class="fw-bold text-white fs-4 mb-2">Quarterly Beast</h3>
            <div class="fw-bold fs-2 text-orange mb-3">₹6,999 <span class="fs-6 text-muted font-body fw-normal">/ 3 months</span></div>
            <p class="text-muted small">Designed for serious fitness enthusiasts with unlimited group sessions.</p>
            <hr class="border-secondary opacity-25">
            <ul class="list-unstyled  small d-flex flex-column gap-2 mb-4">
                <li><i class="fa-solid fa-check text-orange me-2"></i> 24/7 Gym Floor Access</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> Unlimited Group Classes</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> 1 Personal Trainer Session</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> Diet & Nutrition Guidance</li>
            </ul>
            <div class="mt-auto d-flex gap-2">
                <button class="btn btn-gwb-secondary w-100 py-2"><i class="fa-solid fa-pen me-1"></i> Edit Plan</button>
            </div>
        </div>
    </div>

    <!-- Plan Card 3 -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="gwb-card h-100">
            <h3 class="fw-bold text-white fs-4 mb-2">Annual Pro Flex</h3>
            <div class="fw-bold fs-2 text-orange mb-3">₹19,999 <span class="fs-6 text-muted font-body fw-normal">/ year</span></div>
            <p class="text-muted small">All-inclusive VIP membership with dedicated personal trainer support.</p>
            <hr class="border-secondary opacity-25">
            <ul class="list-unstyled  small d-flex flex-column gap-2 mb-4">
                <li><i class="fa-solid fa-check text-orange me-2"></i> VIP All-Access 365 Days</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> Unlimited Classes & Sauna</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> 5 PT Sessions Included</li>
                <li><i class="fa-solid fa-check text-orange me-2"></i> Free Guest Passes (2/mo)</li>
            </ul>
            <div class="mt-auto d-flex gap-2">
                <button class="btn btn-gwb-secondary w-100 py-2"><i class="fa-solid fa-pen me-1"></i> Edit Plan</button>
            </div>
        </div>
    </div>
</div>
@endsection
