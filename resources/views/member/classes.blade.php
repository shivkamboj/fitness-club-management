@extends('layouts.dashboard')

@section('title', 'Group Classes & Schedules')
@section('page_heading', 'Group Classes')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Group Classes & Schedules</h2>
        <p class="text-muted mb-0 small">Browse fitness sessions hosted by your gym center and reserve your seat.</p>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-pills gap-2 mb-4" id="classesTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active px-4" id="available-tab" data-bs-toggle="pill" data-bs-target="#available-pane" type="button" role="tab">
            <i class="fa-solid fa-calendar-days me-2"></i>Available Classes ({{ $availableClasses->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4" id="mybookings-tab" data-bs-toggle="pill" data-bs-target="#mybookings-pane" type="button" role="tab">
            <i class="fa-solid fa-calendar-check me-2"></i>My Bookings ({{ $myBookings->where('status', 'booked')->count() }})
        </button>
    </li>
</ul>

<div class="tab-content" id="classesTabContent">
    <!-- Available Classes Tab Pane -->
    <div class="tab-pane fade show active" id="available-pane" role="tabpanel">
        <div class="row g-3">
            @forelse($availableClasses as $class)
                @php
                    $isBooked = in_array($class->id, $bookedClassIds);
                    $isFull = $class->booked_count >= $class->capacity;
                @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="gwb-card h-100 p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-dark text-info border border-secondary">{{ $class->category ?: 'Fitness' }}</span>
                                <span class="small text-muted"><i class="fa-solid fa-clock me-1 text-orange"></i>{{ $class->duration_minutes }} mins</span>
                            </div>

                            <h4 class="fw-bold text-white mb-1 fs-5">{{ $class->name }}</h4>
                            <p class="text-muted small mb-3">{{ Str::limit($class->description ?: 'High energy group fitness session.', 90) }}</p>

                            <div class="d-flex flex-column gap-2 small text-muted mb-3 bg-secondary p-2.5 rounded">
                                <div><i class="fa-solid fa-user-ninja me-2 text-warning"></i>Trainer: <strong class="text-white">{{ $class->trainer ? $class->trainer->full_name : 'Gym Staff' }}</strong></div>
                                <div><i class="fa-solid fa-calendar-week me-2 text-orange"></i>Days: <strong class="text-white">{{ $class->schedule_days_display }}</strong></div>
                                <div><i class="fa-solid fa-regular fa-clock me-2 text-info"></i>Time: <strong class="text-white">{{ $class->start_time ?: 'TBA' }}</strong></div>
                                @if($class->location)
                                    <div><i class="fa-solid fa-location-dot me-2 text-danger"></i>Studio: <strong class="text-white">{{ $class->location }}</strong></div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2 small">
                                <span class="text-muted">Capacity Status</span>
                                <span class="fw-semibold {{ $isFull ? 'text-danger' : 'text-success' }}">
                                    {{ $class->booked_count }}/{{ $class->capacity }} Seats Booked
                                </span>
                            </div>
                            <div class="progress bg-dark mb-3" style="height: 6px;">
                                <div class="progress-bar {{ $isFull ? 'bg-danger' : 'bg-success' }}" style="width: {{ min(100, ($class->booked_count / max(1, $class->capacity)) * 100) }}%"></div>
                            </div>

                            @if($isBooked)
                                <button class="btn btn-sm btn-outline-success w-100" disabled>
                                    <i class="fa-solid fa-circle-check me-1"></i> Seat Reserved
                                </button>
                            @elseif($isFull)
                                <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                                    <i class="fa-solid fa-ban me-1"></i> Class Full
                                </button>
                            @else
                                <form method="POST" action="{{ route('member.classes.book', $class->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-gwb-primary btn-sm w-100">
                                        <i class="fa-solid fa-plus-circle me-1"></i> Reserve My Seat
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="gwb-card py-5">
                        <i class="fa-solid fa-calendar-xmark fs-1 text-muted mb-3 opacity-50"></i>
                        <h5 class="text-white fw-bold">No Active Group Classes</h5>
                        <p class="text-muted mb-0 small">No group classes are currently scheduled at your gym center.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- My Bookings Tab Pane -->
    <div class="tab-pane fade" id="mybookings-pane" role="tabpanel">
        <div class="gwb-card">
            <div class="table-responsive">
                <table class="gwb-table align-middle">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Category</th>
                            <th>Trainer</th>
                            <th>Time / Schedule</th>
                            <th>Booking Date</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myBookings as $booking)
                        <tr>
                            <td class="fw-bold text-white">{{ $booking->groupClass?->name }}</td>
                            <td><span class="badge bg-dark text-info border border-secondary">{{ $booking->groupClass?->category ?: 'Fitness' }}</span></td>
                            <td class="text-white"><i class="fa-solid fa-user-ninja me-1 text-warning"></i>{{ $booking->groupClass?->trainer?->full_name ?: 'Gym Trainer' }}</td>
                            <td class="text-muted"><i class="fa-regular fa-clock me-1 text-orange"></i>{{ $booking->groupClass?->start_time ?: 'Scheduled' }}</td>
                            <td class="text-muted small">{{ $booking->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td>
                                @if($booking->status === 'booked')
                                    <span class="badge bg-success">Active Booking</span>
                                @else
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($booking->status === 'booked')
                                    <form method="POST" action="{{ route('member.classes.cancel', $booking->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa-solid fa-xmark me-1"></i> Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                You haven't booked any group class seats yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
