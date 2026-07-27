@extends('layouts.dashboard')

@section('title', 'Class Roster — ' . $class->name)
@section('page_heading', 'Class Roster')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-4">
    <a href="{{ route('gym-owner.classes.index') }}" class="text-decoration-none text-muted small">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Classes
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

{{-- Class Header Card --}}
<div class="gwb-card mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-7">
            <div class="d-flex align-items-center gap-3 mb-2">
                <span class="badge bg-dark text-orange border border-secondary">{{ $class->category }}</span>
                <span class="small text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $class->duration_minutes }} mins</span>
                @if($class->status === 'inactive')
                    <span class="badge" style="background:rgba(107,114,128,.3);color:#9ca3af;border:1px solid #374151;">Inactive</span>
                @endif
            </div>
            <h2 class="fw-bold text-white fs-3 mb-1">{{ $class->name }}</h2>
            <p class="text-muted small mb-0">{{ $class->description ?: 'No description provided.' }}</p>
        </div>
        <div class="col-12 col-md-5">
            <div class="row g-2 text-center">
                @php
                    $bookedCount = $bookings->where('status','booked')->count();
                    $attendedCount = $bookings->where('status','attended')->count();
                    $cancelledCount = $bookings->where('status','cancelled')->count();
                    $fillPct  = $class->capacity > 0 ? min(100, round($bookedCount / $class->capacity * 100)) : 0;
                    $barColor = $fillPct >= 90 ? '#ef4444' : ($fillPct >= 70 ? '#f97316' : '#3b82f6');
                @endphp
                <div class="col-4">
                    <div class="p-2 rounded border border-secondary" style="background:var(--gwb-surface-2);">
                        <div class="fw-bold fs-5 text-white">{{ $bookedCount }}</div>
                        <div class="text-muted" style="font-size:.72rem;">Booked</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 rounded border border-secondary" style="background:var(--gwb-surface-2);">
                        <div class="fw-bold fs-5" style="color:#22c55e;">{{ $attendedCount }}</div>
                        <div class="text-muted" style="font-size:.72rem;">Attended</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 rounded border border-secondary" style="background:var(--gwb-surface-2);">
                        <div class="fw-bold fs-5" style="color:#ef4444;">{{ $cancelledCount }}</div>
                        <div class="text-muted" style="font-size:.72rem;">Cancelled</div>
                    </div>
                </div>
            </div>

            {{-- Capacity bar --}}
            <div class="mt-3 px-1">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Capacity</span>
                    <span class="small fw-bold" style="color:{{ $barColor }};">{{ $bookedCount }} / {{ $class->capacity }} Seats</span>
                </div>
                <div class="progress" style="height:6px;background:rgba(255,255,255,.08);border-radius:4px;">
                    <div class="progress-bar" style="width:{{ $fillPct }}%;background:{{ $barColor }};border-radius:4px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedule & Trainer meta --}}
    <div class="d-flex flex-wrap gap-3 mt-3 pt-3 border-top border-secondary">
        @if($class->trainer)
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold"
                     style="width:32px;height:32px;background:var(--gwb-accent);color:#fff;font-size:.72rem;flex-shrink:0;">
                    {{ $class->trainer_initials }}
                </div>
                <span class="small text-white">{{ $class->trainer->full_name }}</span>
            </div>
        @endif
        @if($class->schedule_days && count($class->schedule_days))
            <div class="d-flex flex-wrap gap-1 align-items-center">
                <i class="fa-solid fa-calendar-days text-muted small me-1"></i>
                @foreach($class->schedule_days as $day)
                    <span class="badge" style="background:rgba(59,130,246,.15);color:#93c5fd;border:1px solid rgba(59,130,246,.3);font-size:.7rem;">{{ $day }}</span>
                @endforeach
            </div>
        @endif
        @if($class->start_time)
            <div class="d-flex align-items-center gap-1 small text-muted">
                <i class="fa-solid fa-clock"></i>
                {{ \Carbon\Carbon::parse($class->start_time)->format('g:i A') }}
            </div>
        @endif
        @if($class->location)
            <div class="d-flex align-items-center gap-1 small text-muted">
                <i class="fa-solid fa-location-dot text-orange"></i>
                {{ $class->location }}
            </div>
        @endif
        <a href="{{ route('gym-owner.classes.edit', $class->id) }}" class="btn btn-gwb-secondary btn-sm ms-auto text-decoration-none">
            <i class="fa-solid fa-pen me-1"></i> Edit Class
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- ── Roster Table ── --}}
    <div class="col-12 col-lg-8">
        <div class="gwb-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold text-white fs-5 mb-0">
                    <i class="fa-solid fa-users me-2 text-orange"></i>Enrolled Members
                    <span class="badge ms-2" style="background:rgba(234,88,12,.15);color:#ea580c;border:1px solid rgba(234,88,12,.3);">{{ $bookings->count() }}</span>
                </h3>
            </div>

            @if($bookings->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-solid fa-user-slash text-muted fs-2 mb-3"></i>
                    <p class="text-muted mb-0">No members enrolled yet. Add members from the panel on the right.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg:transparent;--bs-table-hover-bg:rgba(255,255,255,.04);">
                        <thead>
                            <tr style="border-bottom:1px solid var(--gwb-border);">
                                <th class="text-muted fw-semibold small py-3 border-0">#</th>
                                <th class="text-muted fw-semibold small py-3 border-0">Member</th>
                                <th class="text-muted fw-semibold small py-3 border-0">Booked At</th>
                                <th class="text-muted fw-semibold small py-3 border-0">Status</th>
                                <th class="text-muted fw-semibold small py-3 border-0 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $i => $booking)
                                <tr style="border-bottom:1px solid rgba(255,255,255,.04);">
                                    <td class="text-muted small py-3">{{ $i + 1 }}</td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @php
                                                $m = $booking->member;
                                                $initials = $m ? mb_strtoupper(mb_substr($m->first_name ?? $m->name ?? '?', 0, 1) . mb_substr($m->last_name ?? '', 0, 1)) : '?';
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold"
                                                 style="width:34px;height:34px;background:rgba(234,88,12,.2);color:#ea580c;font-size:.72rem;flex-shrink:0;">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-white small">{{ $m?->full_name ?? 'Unknown' }}</div>
                                                <div class="text-muted" style="font-size:.73rem;">{{ $m?->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted small py-3">
                                        {{ $booking->booked_at ? $booking->booked_at->format('d M Y, g:i A') : '—' }}
                                    </td>
                                    <td class="py-3">
                                        <form action="{{ route('gym-owner.classes.booking.status', $booking->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm border-0 fw-semibold"
                                                    onchange="this.form.submit()"
                                                    style="background:transparent;font-size:.78rem;width:auto;
                                                           color:{{ $booking->status === 'attended' ? '#22c55e' : ($booking->status === 'cancelled' ? '#ef4444' : '#93c5fd') }};">
                                                <option value="booked"    {{ $booking->status === 'booked'    ? 'selected' : '' }} style="color:#fff;background:#1e1e1e;">Booked</option>
                                                <option value="attended"  {{ $booking->status === 'attended'  ? 'selected' : '' }} style="color:#fff;background:#1e1e1e;">Attended</option>
                                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }} style="color:#fff;background:#1e1e1e;">Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-3 text-end">
                                        <form action="{{ route('gym-owner.classes.roster.remove', $booking->id) }}" method="POST"
                                              class="d-inline remove-booking-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-booking-btn" title="Remove from roster">
                                                <i class="fa-solid fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Add Member Panel ── --}}
    <div class="col-12 col-lg-4">
        <div class="gwb-card h-100">
            <h3 class="fw-bold text-white fs-5 mb-1">
                <i class="fa-solid fa-user-plus me-2 text-orange"></i>Add Member
            </h3>
            <p class="text-muted small mb-4">Enroll a member directly into this class.</p>

            @if($bookedCount >= $class->capacity)
                <div class="alert border-0 text-white mb-4"
                     style="background:rgba(239,68,68,.15);border-left:4px solid #ef4444!important;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    This class has reached its capacity limit of <strong>{{ $class->capacity }}</strong> members.
                </div>
            @endif

            <form action="{{ route('gym-owner.classes.roster.add', $class->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label text-white small fw-semibold">Select Member</label>
                    <select id="user_id" name="user_id"
                            class="form-select bg-dark border-secondary text-white @error('user_id') is-invalid @enderror"
                            {{ $bookedCount >= $class->capacity ? 'disabled' : '' }} required>
                        <option value="" disabled selected>— Choose a member —</option>
                        @forelse($availableMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }} — {{ $member->email }}</option>
                        @empty
                            <option disabled>All members already enrolled</option>
                        @endforelse
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-gwb-primary w-100"
                        {{ ($bookedCount >= $class->capacity || $availableMembers->isEmpty()) ? 'disabled' : '' }}>
                    <i class="fa-solid fa-user-plus me-1"></i> Add to Roster
                </button>
            </form>

            {{-- Quick stats --}}
            <div class="mt-4 pt-4 border-top border-secondary">
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Available Seats</span>
                    <span class="text-white fw-semibold">{{ max(0, $class->capacity - $bookedCount) }} / {{ $class->capacity }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Total Enrolled</span>
                    <span class="text-white fw-semibold">{{ $bookings->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Attendance Rate</span>
                    <span class="fw-semibold" style="color:#22c55e;">
                        {{ $bookings->count() > 0 ? round($attendedCount / $bookings->count() * 100) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.remove-booking-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const form   = this.closest('.remove-booking-form');
            const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
            Swal.fire({
                title: 'Remove Member?',
                text: 'This will remove the member from the class roster.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Yes, remove',
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
