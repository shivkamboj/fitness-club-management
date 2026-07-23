@extends('layouts.dashboard')

@section('title', 'Admin Dashboard Overview')
@section('page_heading', 'Dashboard Overview')

@section('content')
<!-- Top Quick Actions Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Welcome Back, {{ Auth::user()->name ?? 'Gym Owner' }} 👋</h2>
        <p class="text-muted mb-0 small">Here is what is happening at your fitness center today.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('admin.members.index') }}" class="btn-gwb-primary">
            <i class="fa-solid fa-user-plus"></i> Add New Member
        </a>
        <a href="{{ route('admin.payments.index') }}" class="btn-gwb-secondary">
            <i class="fa-solid fa-receipt"></i> Record Payment
        </a>
    </div>
</div>

<!-- 4 Key Stat Cards -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Total Members -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="stat-trend {{ $stats['total_members']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['total_members']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['total_members']['value'] }}</div>
            <div class="stat-label">{{ $stats['total_members']['label'] }}</div>
        </div>
    </div>

    <!-- Stat 2: Active Subscriptions -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <span class="stat-trend {{ $stats['active_subscriptions']['trend'] }}">
                    <i class="fa-solid fa-circle-check me-1"></i>{{ $stats['active_subscriptions']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['active_subscriptions']['value'] }}</div>
            <div class="stat-label">{{ $stats['active_subscriptions']['label'] }}</div>
        </div>
    </div>

    <!-- Stat 3: Revenue This Month -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </div>
                <span class="stat-trend {{ $stats['revenue_this_month']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['revenue_this_month']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['revenue_this_month']['value'] }}</div>
            <div class="stat-label">{{ $stats['revenue_this_month']['label'] }}</div>
        </div>
    </div>

    <!-- Stat 4: Expiring This Week -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="stat-trend {{ $stats['expiring_this_week']['trend'] }}">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>{{ $stats['expiring_this_week']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['expiring_this_week']['value'] }}</div>
            <div class="stat-label">{{ $stats['expiring_this_week']['label'] }}</div>
        </div>
    </div>
</div>

<!-- Main Section: Sign-up Analytics & Alerts -->
<div class="row g-4 mb-4">
    <!-- Chart Card: 30-Day Member Signups -->
    <div class="col-12 col-lg-8">
        <div class="gwb-card h-100 mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title">
                    <i class="fa-solid fa-chart-line"></i> New Member Sign-ups (Last 30 Days)
                </h3>
                <span class="badge bg-dark border border-secondary text-muted px-2 py-1">Live Updates</span>
            </div>
            <div class="position-relative" style="height: 320px;">
                <canvas id="signupsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Expiring Soon Widget -->
    <div class="col-12 col-lg-4">
        <div class="gwb-card h-100 mb-0 d-flex flex-column">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title text-warning">
                    <i class="fa-solid fa-bell"></i> Expiring Subscriptions
                </h3>
                <a href="{{ route('admin.members.index') }}" class="text-orange text-decoration-none small">View All</a>
            </div>
            <div class="flex-grow-1 overflow-auto">
                <div class="d-flex flex-column gap-3">
                    @foreach($expiringMembers as $exp)
                        <div class="p-3 rounded-3" style="background-color: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-white">{{ $exp['name'] }}</span>
                                <span class="badge-status badge-warning">
                                    <i class="fa-regular fa-clock"></i> {{ $exp['days_left'] }} days left
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">{{ $exp['plan'] }}</span>
                                <span class="small text-faint">Ends {{ $exp['end_date'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-3 pt-3 border-top border-secondary opacity-75 text-center">
                <small class="text-muted"><i class="fa-solid fa-info-circle me-1"></i> Automated renewal emails sent daily</small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Member Signups Table -->
<div class="gwb-card mb-0">
    <div class="gwb-card-header">
        <h3 class="gwb-card-title">
            <i class="fa-solid fa-user-clock"></i> Recent Member Registrations
        </h3>
        <a href="{{ route('admin.members.index') }}" class="btn-gwb-secondary py-1 px-3 fs-7">
            Manage All Members <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="gwb-table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Email Address</th>
                    <th>Assigned Plan</th>
                    <th>Join Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentMembers as $member)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ $member['avatar'] }}
                                </div>
                                <span class="fw-medium text-white">{{ $member['name'] }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $member['email'] }}</td>
                        <td>
                            <span class="badge bg-dark text-orange border border-secondary">{{ $member['plan'] }}</span>
                        </td>
                        <td class="text-muted">{{ $member['join_date'] }}</td>
                        <td>
                            @if($member['status'] === 'Active')
                                <span class="badge-status badge-active">Active</span>
                            @else
                                <span class="badge-status badge-warning">Expiring Soon</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-secondary me-1">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('signupsChart').getContext('2d');
    
    // Create gradient fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(255, 90, 31, 0.4)');
    gradient.addColorStop(1, 'rgba(255, 90, 31, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'New Sign-ups',
                data: {!! json_encode($chartData) !!},
                borderColor: '#ff5a1f',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ff5a1f',
                pointHoverRadius: 6,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#17171f',
                    titleColor: '#f5f3ef',
                    bodyColor: '#ff5a1f',
                    borderColor: '#2a2a35',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(42, 42, 53, 0.5)' },
                    ticks: { color: '#9b9ba8', font: { family: 'Inter', size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(42, 42, 53, 0.5)' },
                    ticks: { color: '#9b9ba8', font: { family: 'Inter', size: 11 }, stepSize: 5 },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
