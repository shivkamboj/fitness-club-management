@extends('layouts.dashboard')

@section('title', 'Super Admin Overview')
@section('page_heading', 'Platform Overview')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Super Admin Console</h2>
        <p class="text-muted mb-0 small">Monitor registered gym centers, platform users, membership volume, and sales inquiries.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('super-admin.gyms.index') }}" class="btn-gwb-primary">
            <i class="fa-solid fa-building-circle-check"></i> Manage Gym Owners
        </a>
        <a href="{{ route('super-admin.contacts.index') }}" class="btn-gwb-secondary">
            <i class="fa-solid fa-envelope-open-text"></i> Review Leads
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
                <span class="stat-trend {{ $stats['total_gyms']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['total_gyms']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['total_gyms']['value'] }}</div>
            <div class="stat-label">{{ $stats['total_gyms']['label'] }}</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <span class="stat-trend {{ $stats['total_users']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['total_users']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['total_users']['value'] }}</div>
            <div class="stat-label">{{ $stats['total_users']['label'] }}@extends('layouts.dashboard')

@section('title', 'Super Admin Overview')
@section('page_heading', 'Platform Overview')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Super Admin Console</h2>
        <p class="text-muted mb-0 small">Monitor registered gym centers, platform users, membership volume, and sales inquiries.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('super-admin.gyms.index') }}" class="btn-gwb-primary">
            <i class="fa-solid fa-building-circle-check"></i> Manage Gym Owners
        </a>
        <a href="{{ route('super-admin.contacts.index') }}" class="btn-gwb-secondary">
            <i class="fa-solid fa-envelope-open-text"></i> Review Leads
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
                <span class="stat-trend {{ $stats['total_gyms']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['total_gyms']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['total_gyms']['value'] }}</div>
            <div class="stat-label">{{ $stats['total_gyms']['label'] }}</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <span class="stat-trend {{ $stats['total_users']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['total_users']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['total_users']['value'] }}</div>
            <div class="stat-label">{{ $stats['total_users']['label'] }}</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                <span class="stat-trend {{ $stats['saas_revenue']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['saas_revenue']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['saas_revenue']['value'] }}</div>
            <div class="stat-label">{{ $stats['saas_revenue']['label'] }}</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-comments"></i></div>
                <span class="stat-trend {{ $stats['contact_requests']['trend'] }}">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $stats['contact_requests']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['contact_requests']['value'] }}</div>
            <div class="stat-label">{{ $stats['contact_requests']['label'] }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="gwb-card mb-0 p-3 d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-muted mb-1">Total Gym Members (Platform-wide)</div>
                <div class="fs-3 fw-bold text-white mb-0">{{ number_format($totalMembers) }}</div>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-user-group"></i></div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="gwb-card mb-0 p-3 d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-muted mb-1">New Members This Month</div>
                <div class="fs-3 fw-bold text-orange mb-0">+{{ number_format($membersThisMonth) }}</div>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="gwb-card mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title"><i class="fa-solid fa-chart-column"></i> New Gym Center Registrations (Last 7 Months)</h3>
            </div>
            <div class="position-relative" style="height: 300px;">
                <canvas id="platformGymsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-7">
        <div class="gwb-card h-100 mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title"><i class="fa-solid fa-building-shield"></i> Registered Gym Centers</h3>
                <a href="{{ route('super-admin.gyms.index') }}" class="btn-gwb-secondary py-1 px-3 fs-7">View All</a>
            </div>
            @if(count($recentGyms))
                <div class="table-responsive">
                    <table class="gwb-table">
                        <thead>
                            <tr>
                                <th>Gym Name</th>
                                <th>Owner</th>
                                <th>Members</th>
                                <th>Plans</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGyms as $gym)
                                <tr>
                                    <td class="fw-semibold text-white">{{ $gym['name'] }}</td>
                                    <td>
                                        <div class="text-white">{{ $gym['owner'] }}</div>
                                        <div class="small text-muted">{{ $gym['email'] }}</div>
                                    </td>
                                    <td class="fw-bold text-orange">{{ $gym['members'] }}</td>
                                    <td><span class="badge bg-dark text-orange border border-secondary">{{ $gym['plan'] }}</span></td>
                                    <td>
                                        <span class="badge-status {{ strtolower($gym['status']) === 'active' ? 'badge-active' : 'badge-warning' }}">
                                            {{ $gym['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fa-solid fa-building fs-4 text-muted"></i>
                    <div class="small text-muted mt-2">No gym centers registered yet.</div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="gwb-card h-100 mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title text-warning"><i class="fa-solid fa-envelope-open"></i> Recent Inquiries</h3>
                <a href="{{ route('super-admin.contacts.index') }}" class="text-orange small">View Leads</a>
            </div>
            <div class="d-flex flex-column gap-3">
                @forelse($recentContacts as $contact)
                    <div class="p-3 rounded-3 {{ $contact['is_unread'] ? 'border border-warning' : '' }}" style="background-color: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-white">{{ $contact['name'] }}</span>
                            <span class="small text-muted">{{ $contact['date'] }}</span>
                        </div>
                        @if($contact['email'] !== '—')
                            <div class="small text-muted mb-1"><i class="fa-solid fa-envelope me-1"></i> {{ $contact['email'] }}</div>
                        @endif
                        <p class="small text-muted mb-0">{{ $contact['message'] }}</p>
                        @if($contact['is_unread'])
                            <span class="badge-status badge-warning mt-2">Unread</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fa-regular fa-envelope fs-4 text-muted"></i>
                        <div class="small text-muted mt-2">No contact inquiries yet.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('platformGymsChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(255, 90, 31, 0.45)');
    gradient.addColorStop(1, 'rgba(255, 90, 31, 0.05)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'New Gyms',
                data: {!! json_encode($chartData) !!},
                backgroundColor: gradient,
                borderColor: '#ff5a1f',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#9b9ba8' } },
                y: { grid: { color: 'rgba(42, 42, 53, 0.5)' }, ticks: { color: '#9b9ba8', stepSize: 1, precision: 0 }, beginAtZero: true }
            }
        }
    });
});
</script>
@endpush

        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                <span class="stat-trend {{ $stats['saas_revenue']['trend'] }}">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $stats['saas_revenue']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['saas_revenue']['value'] }}</div>
            <div class="stat-label">{{ $stats['saas_revenue']['label'] }}</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon"><i class="fa-solid fa-comments"></i></div>
                <span class="stat-trend {{ $stats['contact_requests']['trend'] }}">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $stats['contact_requests']['growth'] }}
                </span>
            </div>
            <div class="stat-value">{{ $stats['contact_requests']['value'] }}</div>
            <div class="stat-label">{{ $stats['contact_requests']['label'] }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="gwb-card mb-0 p-3 d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-muted mb-1">Total Gym Members (Platform-wide)</div>
                <div class="fs-3 fw-bold text-white mb-0">{{ number_format($totalMembers) }}</div>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-user-group"></i></div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="gwb-card mb-0 p-3 d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-muted mb-1">New Members This Month</div>
                <div class="fs-3 fw-bold text-orange mb-0">+{{ number_format($membersThisMonth) }}</div>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="gwb-card mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title"><i class="fa-solid fa-chart-column"></i> New Gym Center Registrations (Last 7 Months)</h3>
            </div>
            <div class="position-relative" style="height: 300px;">
                <canvas id="platformGymsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-7">
        <div class="gwb-card h-100 mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title"><i class="fa-solid fa-building-shield"></i> Registered Gym Centers</h3>
                <a href="{{ route('super-admin.gyms.index') }}" class="btn-gwb-secondary py-1 px-3 fs-7">View All</a>
            </div>
            @if(count($recentGyms))
                <div class="table-responsive">
                    <table class="gwb-table">
                        <thead>
                            <tr>
                                <th>Gym Name</th>
                                <th>Owner</th>
                                <th>Members</th>
                                <th>Plans</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGyms as $gym)
                                <tr>
                                    <td class="fw-semibold text-white">{{ $gym['name'] }}</td>
                                    <td>
                                        <div class="text-white">{{ $gym['owner'] }}</div>
                                        <div class="small text-muted">{{ $gym['email'] }}</div>
                                    </td>
                                    <td class="fw-bold text-orange">{{ $gym['members'] }}</td>
                                    <td><span class="badge bg-dark text-orange border border-secondary">{{ $gym['plan'] }}</span></td>
                                    <td>
                                        <span class="badge-status {{ strtolower($gym['status']) === 'active' ? 'badge-active' : 'badge-warning' }}">
                                            {{ $gym['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fa-solid fa-building fs-4 text-muted"></i>
                    <div class="small text-muted mt-2">No gym centers registered yet.</div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="gwb-card h-100 mb-0">
            <div class="gwb-card-header">
                <h3 class="gwb-card-title text-warning"><i class="fa-solid fa-envelope-open"></i> Recent Inquiries</h3>
                <a href="{{ route('super-admin.contacts.index') }}" class="text-orange small">View Leads</a>
            </div>
            <div class="d-flex flex-column gap-3">
                @forelse($recentContacts as $contact)
                    <div class="p-3 rounded-3 {{ $contact['is_unread'] ? 'border border-warning' : '' }}" style="background-color: var(--gwb-surface-2); border: 1px solid var(--gwb-border);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-white">{{ $contact['name'] }}</span>
                            <span class="small text-muted">{{ $contact['date'] }}</span>
                        </div>
                        @if($contact['email'] !== '—')
                            <div class="small text-muted mb-1"><i class="fa-solid fa-envelope me-1"></i> {{ $contact['email'] }}</div>
                        @endif
                        <p class="small text-muted mb-0">{{ $contact['message'] }}</p>
                        @if($contact['is_unread'])
                            <span class="badge-status badge-warning mt-2">Unread</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fa-regular fa-envelope fs-4 text-muted"></i>
                        <div class="small text-muted mt-2">No contact inquiries yet.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('platformGymsChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(255, 90, 31, 0.45)');
    gradient.addColorStop(1, 'rgba(255, 90, 31, 0.05)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'New Gyms',
                data: {!! json_encode($chartData) !!},
                backgroundColor: gradient,
                borderColor: '#ff5a1f',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#9b9ba8' } },
                y: { grid: { color: 'rgba(42, 42, 53, 0.5)' }, ticks: { color: '#9b9ba8', stepSize: 1, precision: 0 }, beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
