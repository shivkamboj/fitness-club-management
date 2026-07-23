@extends('layouts.dashboard')

@section('title', 'Gym Settings & Branding')
@section('page_heading', 'Gym Settings')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Settings & Branding</h2>
        <p class="text-muted mb-0 small">Configure your gym info, opening hours, staff accounts, and payment gateway keys.</p>
    </div>
</div>

<div class="gwb-card">
    <form action="#" method="POST">
        @csrf
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label class="form-label text-muted small fw-semibold text-uppercase">Gym Name</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" value="{{ Auth::user()->gym_name ?? 'Iron Pulse Gym' }}">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label text-muted small fw-semibold text-uppercase">Contact Phone</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" value="+91 98765 43210">
            </div>
            <div class="col-12">
                <label class="form-label text-muted small fw-semibold text-uppercase">Gym Address</label>
                <input type="text" class="form-control bg-dark border-secondary text-white" value="123 Fitness Boulevard, Sector 18, City, State">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label text-muted small fw-semibold text-uppercase">Razorpay Key ID</label>
                <input type="text" class="form-control bg-dark border-secondary text-white mono" value="rzp_test_98327498234">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label text-muted small fw-semibold text-uppercase">Razorpay Key Secret</label>
                <input type="password" class="form-control bg-dark border-secondary text-white mono" value="••••••••••••••••">
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" class="btn-gwb-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
