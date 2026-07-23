@extends('layouts.dashboard')

@section('title', 'Reports & Analytics')
@section('page_heading', 'Reports & Analytics')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Reports & Analytics</h2>
        <p class="text-muted mb-0 small">Track revenue breakdown, peak attendance footfall, and member retention rates.</p>
    </div>
    <div>
        <button class="btn-gwb-secondary">
            <i class="fa-solid fa-download me-1"></i> Export Report (CSV)
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-6">
        <div class="gwb-card h-100">
            <h3 class="gwb-card-title mb-3"><i class="fa-solid fa-pie-chart"></i> Revenue by Plan Type</h3>
            <div class="p-3 bg-dark rounded border border-secondary text-center">
                <div class="row text-center">
                    <div class="col-4 border-end border-secondary">
                        <div class="fw-bold fs-3 text-orange">62%</div>
                        <div class="small text-muted">Annual Pro</div>
                    </div>
                    <div class="col-4 border-end border-secondary">
                        <div class="fw-bold fs-3 text-white">26%</div>
                        <div class="small text-muted">Quarterly</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-3 text-white">12%</div>
                        <div class="small text-muted">Monthly</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="gwb-card h-100">
            <h3 class="gwb-card-title mb-3"><i class="fa-solid fa-clock"></i> Busiest Gym Hours Heatmap</h3>
            <div class="p-3 bg-dark rounded border border-secondary text-center">
                <div class="d-flex justify-content-around">
                    <div>
                        <div class="fw-bold text-orange fs-4">6 AM - 9 AM</div>
                        <div class="small text-muted">Morning Peak (92% Cap)</div>
                    </div>
                    <div>
                        <div class="fw-bold text-orange fs-4">5 PM - 9 PM</div>
                        <div class="small text-muted">Evening Peak (98% Cap)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
