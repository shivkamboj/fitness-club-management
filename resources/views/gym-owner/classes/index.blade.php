@extends('layouts.dashboard')

@section('title', 'Group Classes & Schedules')
@section('page_heading', 'Group Classes & Schedules')

@section('content')

{{-- Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Group Classes &amp; Schedules</h2>
        <p class="text-muted mb-0 small">Manage gym group sessions, assigned trainers, and seat availability.</p>
    </div>
    <a href="{{ route('gym-owner.classes.create') }}" class="btn btn-gwb-primary py-2 px-3 text-decoration-none">
        <i class="fa-solid fa-plus me-1"></i> Add Class
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show mb-4 border-0 text-white"
         style="background:rgba(34,197,94,.15);border-left:4px solid #22c55e!important;" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-dismissible fade show mb-4 border-0 text-white"
         style="background:rgba(239,68,68,.15);border-left:4px solid #ef4444!important;" role="alert">
        <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter Bar --}}
<form method="GET" action="{{ route('gym-owner.classes.index') }}" class="mb-4">
    <div class="d-flex gap-2 flex-wrap">
        <select name="category" class="form-select form-select-sm bg-dark border-secondary text-white" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach(['CrossFit','Yoga','Pilates','Zumba','HIIT','Boxing','Cycling','Aerobics','Strength','Stretching','Dance','Kickboxing','Martial Arts','Swimming','Other'] as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white" style="max-width:150px;" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @if(request('category') || request('status'))
            <a href="{{ route('gym-owner.classes.index') }}" class="btn btn-sm btn-gwb-secondary">
                <i class="fa-solid fa-xmark me-1"></i>Clear
            </a>
        @endif
    </div>
</form>

{{-- Class Cards Grid --}}
<div class="row g-4">
    @forelse($classes as $class)
        @php
            $booked   = $class->booked_seats_count ?? 0;
            $capacity = $class->capacity;
            $fillPct  = $capacity > 0 ? min(100, round($booked / $capacity * 100)) : 0;
            $barColor = $fillPct >= 90 ? '#ef4444' : ($fillPct >= 70 ? '#f97316' : '#3b82f6');
        @endphp
        <div class="col-12 col-md-6 col-lg-4">
            <div class="gwb-card h-100 d-flex flex-column" style="position:relative;">

                {{-- Status badge (top-right absolute) --}}
                @if($class->status === 'inactive')
                    <span class="badge position-absolute" style="top:12px;right:12px;background:rgba(107,114,128,.3);color:#9ca3af;border:1px solid #374151;">Inactive</span>
                @endif

                {{-- Category + Duration --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-dark text-orange border border-secondary">{{ $class->category }}</span>
                    <span class="small text-muted">
                        <i class="fa-regular fa-clock me-1"></i>{{ $class->duration_minutes }} mins
                    </span>
                </div>

                {{-- Name & Description --}}
                <h3 class="fw-bold text-white fs-4 mb-1">{{ $class->name }}</h3>
                <p class="text-muted small mb-3">{{ $class->description ?: 'No description provided.' }}</p>

                {{-- Schedule Chips --}}
                @if($class->schedule_days && count($class->schedule_days))
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        @foreach($class->schedule_days as $day)
                            <span class="badge" style="background:rgba(59,130,246,.15);color:#93c5fd;border:1px solid rgba(59,130,246,.3);font-size:0.7rem;">{{ $day }}</span>
                        @endforeach
                        @if($class->start_time)
                            <span class="badge" style="background:rgba(168,85,247,.15);color:#c4b5fd;border:1px solid rgba(168,85,247,.3);font-size:0.7rem;">
                                <i class="fa-solid fa-clock me-1"></i>{{ \Carbon\Carbon::parse($class->start_time)->format('g:i A') }}
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Trainer --}}
                @if($class->trainer)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold"
                             style="width:30px;height:30px;background:var(--gwb-accent);color:#fff;font-size:0.7rem;flex-shrink:0;">
                            {{ $class->trainer_initials }}
                        </div>
                        <span class="small text-white fw-semibold">Trainer: {{ $class->trainer->full_name }}</span>
                    </div>
                @else
                    <p class="small text-muted mb-3"><i class="fa-solid fa-user-slash me-1"></i>No trainer assigned</p>
                @endif

                {{-- Location --}}
                @if($class->location)
                    <p class="small text-muted mb-3">
                        <i class="fa-solid fa-location-dot me-1 text-orange"></i>{{ $class->location }}
                    </p>
                @endif

                {{-- Capacity Bar --}}
                <div class="p-3 rounded border border-secondary mb-3 small" style="background:var(--gwb-surface-2);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Capacity</span>
                        <span class="fw-bold" style="color:{{ $barColor }};">{{ $booked }} / {{ $capacity }} Booked</span>
                    </div>
                    <div class="progress" style="height:6px;background:rgba(255,255,255,.08);border-radius:4px;">
                        <div class="progress-bar" role="progressbar"
                             style="width:{{ $fillPct }}%;background:{{ $barColor }};border-radius:4px;transition:width .4s ease;">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('gym-owner.classes.roster', $class->id) }}"
                       class="btn btn-gwb-secondary py-2 flex-fill text-decoration-none small text-center">
                        <i class="fa-solid fa-users me-1"></i> View Roster
                    </a>
                    <a href="{{ route('gym-owner.classes.edit', $class->id) }}"
                       class="btn btn-gwb-secondary py-2 px-3 small text-decoration-none" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('gym-owner.classes.destroy', $class->id) }}" method="POST" class="delete-class-form d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger py-2 px-3 small delete-class-btn" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="gwb-card p-5 mx-auto" style="max-width:500px;">
                <i class="fa-solid fa-calendar-days text-muted fs-1 mb-3"></i>
                <h3 class="fw-bold text-white fs-4 mb-2">No Classes Scheduled</h3>
                <p class="text-muted mb-4">Start by creating your first group class to manage sessions and member bookings.</p>
                <a href="{{ route('gym-owner.classes.create') }}" class="btn btn-gwb-primary py-2 px-4 text-decoration-none">
                    <i class="fa-solid fa-plus me-1"></i> Add Group Class
                </a>
            </div>
        </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-class-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const form    = this.closest('.delete-class-form');
            const isDark  = document.documentElement.getAttribute('data-theme') !== 'light';

            Swal.fire({
                title: 'Delete Group Class?',
                text: 'This will permanently remove the class and all member bookings. This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                background: isDark ? 'var(--gwb-surface-1, #1e1e1e)' : '#ffffff',
                color: isDark ? '#ffffff' : '#1e1e1e',
                customClass: { popup: isDark ? 'border border-secondary rounded-3' : 'border border-light-subtle rounded-3 shadow' }
            }).then(r => { if (r.isConfirmed) form.submit(); });
        });
    });
});
</script>
@endpush
