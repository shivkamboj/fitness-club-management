@extends('layouts.dashboard')

@section('title', 'Trainer Dashboard')
@section('page_heading', 'Trainer Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Welcome, {{ $trainer->full_name }}</h2>
        <p class="text-muted mb-0 small">
            {{ $trainer->specialization ?: 'Trainer' }}
            @if($trainer->gym_name)
                · {{ $trainer->gym_name }}
            @endif
        </p>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="gwb-card h-100">
            <h3 class="fw-bold text-white fs-5 mb-3">Your Profile</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small mb-1">Email</div>
                    <div class="text-white">{{ $trainer->email }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1">Phone</div>
                    <div class="text-white">{{ $trainer->phone ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1">Experience</div>
                    <div class="text-white">{{ $trainer->experience !== null ? $trainer->experience.' years' : '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1">Joining Date</div>
                    <div class="text-white">{{ optional($trainer->joining_date)->format('M d, Y') ?: '—' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted small mb-1">Skills</div>
                    <div class="text-white">{{ $trainer->skills ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="gwb-card h-100 text-center">
            @if($trainer->profile_image)
                <img src="{{ asset('storage/'.$trainer->profile_image) }}"
                     alt="{{ $trainer->full_name }}"
                     class="rounded-circle mb-3"
                     style="width:96px;height:96px;object-fit:cover;"
                     loading="lazy">
            @else
                <div class="user-avatar mx-auto mb-3" style="width:96px;height:96px;font-size:1.75rem;">
                    {{ strtoupper(substr($trainer->first_name ?: $trainer->name, 0, 1).substr($trainer->last_name ?: '', 0, 1)) }}
                </div>
            @endif
            <h3 class="fw-bold text-white fs-4 mb-1">{{ $trainer->full_name }}</h3>
            <span class="badge-status badge-active">Active</span>
        </div>
    </div>
</div>
@endsection
