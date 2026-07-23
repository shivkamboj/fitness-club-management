@extends('layouts.dashboard')

@section('title', 'Gym Owner Dashboard')
@section('page_heading', 'Gym Dashboard')

@section('content')
<!-- Top Quick Actions Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Gym Owner Portal 👋</h2>
        <p class="text-muted mb-0 small">Manage your members, personal trainers, workout & diet plans, and class schedules.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('gym-owner.members.index') }}" class="btn-gwb-primary">
            <i class="fa-solid fa-user-plus"></i> Add New Member
        </a>
        <a href="{{ route('gym-owner.workout-plans.index') }}" class="btn-gwb-secondary">
            <i class="fa-solid fa-dumbbell"></i> Workout Routine
        </a>
        <a href="{{ route('gym-owner.diet-plans.index') }}" class="btn-gwb-secondary">
            <i class="fa-solid fa-apple-whole"></i> Diet Routine
        </a>
    </div>
</div>

<!-- 4 Key Gym Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Active Members -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <span class="stat-trend {{ $stats['total_members']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['total_members']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['total_members']['value'] }}</div>
            <div class="stat-label">{{ $stats['total_members']['label'] }}</div>
        </div>
    </div>

    <!-- Trainers Count -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-user-ninja"></i></div>
                <span class="stat-trend {{ $stats['trainers_count']['trend'] }}">
                    <i class="fa-solid fa-check me-1"></i>{{ $stats['trainers_count']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['trainers_count']['value'] }}</div>
            <div class="stat-label">{{ $stats['trainers_count']['label'] }}</div>
        </div>
    </div>

    <!-- Monthly Gym Revenue -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                <span class="stat-trend {{ $stats['revenue_this_month']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['revenue_this_month']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['revenue_this_month']['value'] }}</div>
            <div class="stat-label">{{ $stats['revenue_this_month']['label'] }}</div>
        </div>
    </div>

    <!-- Today's Classes -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <span class="stat-trend {{ $stats['today_classes']['trend'] }}">
                    <i class="fa-solid fa-fire me-1"></i>{{ $stats['today_classes']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['today_classes']['value'] }}</div>
            <div class="stat-label">{{ $stats['today_classes']['label'] }}</div>
        </div>
    </div>
</div>

<!-- Sign-up Graph & Expiration Alerts -->
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="gwb-card h-100 mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title"><i class="fa-solid fa-chart-line"></i> Member Registrations (Last 30 Days)</h3>
            </div>
            <div class="position-relative" style="height: 320px;">
                <canvas id="gymSignupsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="gwb-card h-100 mb-0 d-flex flex-column">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title text-warning"><i class="fa-solid fa-bell"></i> Subscriptions Expiring Soon</h3>
            </div>
            <div class="flex-grow-1 overflow-auto">
                @foreach($expiringMembers as $exp)
                    <div class="p-3 rounded-3 mb-2" style="background-color: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-white">{{ $exp['name'] }}</span>
                            <span class="badge-status badge-warning"><i class="fa-regular fa-clock"></i> {{ $exp['days_left'] }} days</span>
                        </div>
                        <div class="small text-muted">{{ $exp['plan'] }} • Ends {{ $exp['end_date'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Quick Shortcuts to Gym Management Modules -->
<div class="gwb-card mb-0">
    <div class="gwb-card-header">
        <h3 class="gwb-card-title"><i class="fa-solid fa-grid-2"></i> Gym Management Quick Actions</h3>
    </div>
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('gym-owner.members.index') }}" class="p-3 rounded-3 d-block text-decoration-none" style="background: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                <i class="fa-solid fa-users fs-3 text-orange mb-2"></i>
                <div class="fw-semibold text-white">Manage Members</div>
                <div class="small text-muted">View profile & renewals</div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('gym-owner.trainers.index') }}" class="p-3 rounded-3 d-block text-decoration-none" style="background: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                <i class="fa-solid fa-user-ninja fs-3 text-orange mb-2"></i>
                <div class="fw-semibold text-white">Manage Trainers</div>
                <div class="small text-muted">Rates & availability</div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('gym-owner.workout-plans.index') }}" class="p-3 rounded-3 d-block text-decoration-none" style="background: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                <i class="fa-solid fa-dumbbell fs-3 text-orange mb-2"></i>
                <div class="fw-semibold text-white">Workout Plans</div>
                <div class="small text-muted">Routines & exercises</div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{ route('gym-owner.diet-plans.index') }}" class="p-3 rounded-3 d-block text-decoration-none" style="background: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                <i class="fa-solid fa-apple-whole fs-3 text-orange mb-2"></i>
                <div class="fw-semibold text-white">Diet Plans</div>
                <div class="small text-muted">Nutrition & macros</div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('gymSignupsChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(255, 90, 31, 0.4)');
    gradient.addColorStop(1, 'rgba(255, 90, 31, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Sign-ups',
                data: {!! json_encode($chartData) !!},
                borderColor: '#ff5a1f',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(42, 42, 53, 0.5)' }, ticks: { color: '#9b9ba8' } },
                y: { grid: { color: 'rgba(42, 42, 53, 0.5)' }, ticks: { color: '#9b9ba8' }, beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
