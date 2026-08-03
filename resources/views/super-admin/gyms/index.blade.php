@extends('layouts.dashboard')

@section('title', 'Gym Owners & Registered Centers')
@section('page_heading', 'Gym Owners Management')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Gym Owners & Registered Centers</h2>
        <p class="text-muted mb-0 small">Register new gym signups, update accounts, toggle tenant access, and view statistics.</p>
    </div>
    <div>
        <button class="btn-gwb-primary" data-bs-toggle="modal" data-bs-target="#addGymOwnerModal">
            <i class="fa-solid fa-plus me-1"></i> Register Gym Center
        </button>
    </div>
</div>

<!-- Display Validation Errors -->
@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
            <div>
                <strong>Please correct the errors below:</strong>
                <ul class="mb-0 mt-1 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Dynamic Summary Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Total Centers</span>
                    <h3 class="fw-bold text-white mb-0 mt-1">{{ number_format($stats['total']) }}</h3>
                    <span class="small text-success"><i class="fa-solid fa-arrow-up me-1"></i>+{{ $stats['new_this_month'] }} this month</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-orange border border-secondary">
                    <i class="fa-solid fa-building-shield fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Active Centers</span>
                    <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($stats['active']) }}</h3>
                    <span class="small text-muted">Operational Gyms</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-success border border-secondary">
                    <i class="fa-solid fa-circle-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Inactive / Suspended</span>
                    <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($stats['inactive']) }}</h3>
                    <span class="small text-muted">Access Paused</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-warning border border-secondary">
                    <i class="fa-solid fa-circle-pause fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Platform Members</span>
                    <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($stats['total_members']) }}</h3>
                    <span class="small text-muted">Across all centers</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-info border border-secondary">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="gwb-card mb-4 p-3">
    <form method="GET" action="{{ route('super-admin.gyms.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-dark text-muted border-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search by Gym Name, Owner, Email, Phone..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-12 col-md-3">
            <select name="status" class="form-select bg-dark text-white border-secondary">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="submit" class="btn-gwb-primary flex-grow-1">
                <i class="fa-solid fa-filter me-1"></i> Apply Filter
            </button>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('super-admin.gyms.index') }}" class="btn btn-outline-secondary d-flex align-items-center px-3" title="Clear Filters">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Gym Centers Directory Table Card -->
<div class="gwb-card">
    <div class="table-responsive">
        <table class="gwb-table align-middle">
            <thead>
                <tr>
                    <th>Gym Center & Location</th>
                    <th>Owner Details</th>
                    <th class="text-center">Members</th>
                    <th class="text-center">Trainers</th>
                    <th class="text-center">Active Plans</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gyms as $gym)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-2 p-2 bg-dark text-orange border border-secondary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                <i class="fa-solid fa-dumbbell fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white fs-6">
                                    {{ $gym->gym_name ?: ($gym->full_name . ' Gym') }}
                                </div>
                                <div class="small text-muted">
                                    <i class="fa-solid fa-location-dot me-1 text-orange"></i>{{ $gym->city }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="fw-semibold text-white">{{ $gym->name }}</div>
                        <div class="small text-muted">
                            <i class="fa-regular fa-envelope me-1"></i>{{ $gym->email }}
                        </div>
                        @if($gym->phone)
                            <div class="small text-muted">
                                <i class="fa-solid fa-phone me-1"></i>{{ $gym->phone }}
                            </div>
                        @endif
                    </td>

                    <td class="text-center">
                        <span class="badge bg-dark text-white border border-secondary px-2 py-1 fs-6">
                            <i class="fa-solid fa-users text-info me-1"></i> {{ number_format($gym->members_count) }}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="badge bg-dark text-white border border-secondary px-2 py-1 fs-6">
                            <i class="fa-solid fa-user-ninja text-warning me-1"></i> {{ number_format($gym->trainers_count) }}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="badge bg-dark text-orange border border-secondary px-2 py-1">
                            {{ $gym->active_plans_count }} Active {{ Str::plural('Plan', $gym->active_plans_count) }}
                        </span>
                    </td>

                    <td>
                        <form method="POST" action="{{ route('super-admin.gyms.toggle-status', $gym->id) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm p-0 border-0 background-none" title="Click to toggle status">
                                @if($gym->status === 'active')
                                    <span class="badge bg-success text-white px-2 py-1 rounded-pill cursor-pointer">
                                        <i class="fa-solid fa-circle-check me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white px-2 py-1 rounded-pill cursor-pointer">
                                        <i class="fa-solid fa-circle-pause me-1"></i> Inactive
                                    </span>
                                @endif
                            </button>
                        </form>
                    </td>

                    <td class="text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <button
                                type="button"
                                class="btn btn-outline-light view-gym-btn"
                                data-url="{{ route('super-admin.gyms.show', $gym->id) }}"
                                title="View Gym Details"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <button
                                type="button"
                                class="btn btn-outline-warning edit-gym-btn"
                                data-id="{{ $gym->id }}"
                                data-gym_name="{{ $gym->gym_name }}"
                                data-name="{{ $gym->name }}"
                                data-email="{{ $gym->email }}"
                                data-phone="{{ $gym->phone }}"
                                data-city="{{ $gym->city }}"
                                data-status="{{ $gym->status }}"
                                data-url="{{ route('super-admin.gyms.update', $gym->id) }}"
                                title="Edit Gym Center"
                            >
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <form method="POST" action="{{ route('super-admin.gyms.destroy', $gym->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to remove {{ $gym->gym_name ?: $gym->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Delete Gym Owner">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <i class="fa-solid fa-dumbbell fs-1 text-muted mb-3 opacity-50"></i>
                            <h5 class="text-white fw-bold">No Gym Centers Found</h5>
                            <p class="text-muted mb-3 small">No registered gym owners matched your search query or filter criteria.</p>
                            @if(request()->filled('search') || request()->filled('status'))
                                <a href="{{ route('super-admin.gyms.index') }}" class="btn-gwb-secondary btn-sm me-2">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Search Filter
                                </a>
                            @endif
                            <button class="btn-gwb-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGymOwnerModal">
                                <i class="fa-solid fa-plus me-1"></i> Register New Gym
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($gyms->hasPages())
    <div class="p-3 border-top border-secondary opacity-75">
        {{ $gyms->links() }}
    </div>
    @endif
</div>

<!-- ========================================================================= -->
<!-- MODAL: ADD GYM OWNER -->
<!-- ========================================================================= -->
<div class="modal fade" id="addGymOwnerModal" tabindex="-1" aria-labelledby="addGymOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-white" id="addGymOwnerModalLabel">
                    <i class="fa-solid fa-plus-circle text-orange me-2"></i> Register New Gym Owner
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('super-admin.gyms.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white fw-semibold">Gym Center Name <span class="text-danger">*</span></label>
                        <input type="text" name="gym_name" class="form-control bg-secondary text-white border-0" placeholder="e.g. Iron Pulse Fitness Center" value="{{ old('gym_name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white fw-semibold">Owner Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-secondary text-white border-0" placeholder="e.g. Vikram Malhotra" value="{{ old('name') }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-secondary text-white border-0" placeholder="owner@gym.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control bg-secondary text-white border-0" placeholder="+91 9876543210" value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">City / Location</label>
                            <input type="text" name="city" class="form-control bg-secondary text-white border-0" placeholder="e.g. Mumbai, MH" value="{{ old('city') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select bg-secondary text-white border-0" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white fw-semibold">Owner Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control bg-secondary text-white border-0" placeholder="Minimum 6 characters" required>
                    </div>
                </div>

                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-gwb-primary">
                        <i class="fa-solid fa-check me-1"></i> Register Gym Center
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: EDIT GYM OWNER -->
<!-- ========================================================================= -->
<div class="modal fade" id="editGymOwnerModal" tabindex="-1" aria-labelledby="editGymOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-white" id="editGymOwnerModalLabel">
                    <i class="fa-solid fa-pen text-orange me-2"></i> Edit Gym Center Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editGymForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white fw-semibold">Gym Center Name <span class="text-danger">*</span></label>
                        <input type="text" id="edit_gym_name" name="gym_name" class="form-control bg-secondary text-white border-0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white fw-semibold">Owner Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control bg-secondary text-white border-0" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="edit_email" name="email" class="form-control bg-secondary text-white border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">Phone Number</label>
                            <input type="text" id="edit_phone" name="phone" class="form-control bg-secondary text-white border-0">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">City / Location</label>
                            <input type="text" id="edit_city" name="city" class="form-control bg-secondary text-white border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white fw-semibold">Status <span class="text-danger">*</span></label>
                            <select id="edit_status" name="status" class="form-select bg-secondary text-white border-0" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white fw-semibold">New Password (Optional)</label>
                        <input type="password" name="password" class="form-control bg-secondary text-white border-0" placeholder="Leave blank to keep unchanged">
                    </div>
                </div>

                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-gwb-primary">
                        <i class="fa-solid fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: VIEW GYM DETAILS -->
<!-- ========================================================================= -->
<div class="modal fade" id="viewGymModal" tabindex="-1" aria-labelledby="viewGymModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="viewGymModalLabel">
                    <i class="fa-solid fa-dumbbell text-orange"></i>
                    <span id="view_gym_title">Gym Details</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="viewGymLoading" class="text-center py-4">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="text-muted mt-2 small">Loading gym statistics...</p>
                </div>

                <div id="viewGymContent" class="d-none">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-secondary">
                        <div>
                            <h4 id="view_gym_name" class="fw-bold text-white mb-0">Iron Pulse Fitness</h4>
                            <span id="view_gym_city" class="text-muted small"><i class="fa-solid fa-location-dot text-orange me-1"></i>Mumbai, MH</span>
                        </div>
                        <span id="view_gym_status_badge" class="badge bg-success">Active</span>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="p-3 bg-secondary rounded text-center">
                                <i class="fa-solid fa-users text-info fs-4 mb-1"></i>
                                <div id="view_members_count" class="fw-bold text-white fs-5">0</div>
                                <div class="small text-muted">Registered Members</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-secondary rounded text-center">
                                <i class="fa-solid fa-user-ninja text-warning fs-4 mb-1"></i>
                                <div id="view_trainers_count" class="fw-bold text-white fs-5">0</div>
                                <div class="small text-muted">Assigned Trainers</div>
                            </div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush bg-transparent">
                        <div class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between px-0">
                            <span class="text-muted">Owner Name:</span>
                            <span id="view_owner_name" class="fw-semibold">—</span>
                        </div>
                        <div class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between px-0">
                            <span class="text-muted">Email:</span>
                            <span id="view_owner_email" class="fw-semibold">—</span>
                        </div>
                        <div class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between px-0">
                            <span class="text-muted">Phone:</span>
                            <span id="view_owner_phone" class="fw-semibold">—</span>
                        </div>
                        <div class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between px-0">
                            <span class="text-muted">Membership Plans:</span>
                            <span id="view_plans_count" class="fw-semibold">—</span>
                        </div>
                        <div class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between px-0">
                            <span class="text-muted">Joined Platform:</span>
                            <span id="view_registered_at" class="fw-semibold">—</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Edit Gym Modal Population ──────────────────────────────────────────────
    const editGymBtns = document.querySelectorAll('.edit-gym-btn');
    const editGymModal = new bootstrap.Modal(document.getElementById('editGymOwnerModal'));
    const editGymForm = document.getElementById('editGymForm');

    editGymBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            editGymForm.action = url;

            document.getElementById('edit_gym_name').value = this.getAttribute('data-gym_name') || '';
            document.getElementById('edit_name').value = this.getAttribute('data-name') || '';
            document.getElementById('edit_email').value = this.getAttribute('data-email') || '';
            document.getElementById('edit_phone').value = this.getAttribute('data-phone') || '';
            document.getElementById('edit_city').value = this.getAttribute('data-city') || '';
            document.getElementById('edit_status').value = this.getAttribute('data-status') || 'active';

            editGymModal.show();
        });
    });

    // ── View Gym Modal AJAX Fetch ──────────────────────────────────────────────
    const viewGymBtns = document.querySelectorAll('.view-gym-btn');
    const viewGymModal = new bootstrap.Modal(document.getElementById('viewGymModal'));
    const loadingEl = document.getElementById('viewGymLoading');
    const contentEl = document.getElementById('viewGymContent');

    viewGymBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const url = this.getAttribute('data-url');

            loadingEl.classList.remove('d-none');
            contentEl.classList.add('d-none');
            viewGymModal.show();

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('view_gym_title').textContent = data.gym_name;
                document.getElementById('view_gym_name').textContent = data.gym_name;
                document.getElementById('view_gym_city').innerHTML = '<i class="fa-solid fa-location-dot text-orange me-1"></i>' + data.city;

                const statusBadge = document.getElementById('view_gym_status_badge');
                statusBadge.textContent = data.status.toUpperCase();
                statusBadge.className = data.status === 'active' ? 'badge bg-success' : 'badge bg-danger';

                document.getElementById('view_members_count').textContent = data.members_count;
                document.getElementById('view_trainers_count').textContent = data.trainers_count;
                document.getElementById('view_owner_name').textContent = data.full_name;
                document.getElementById('view_owner_email').textContent = data.email;
                document.getElementById('view_owner_phone').textContent = data.phone;
                document.getElementById('view_plans_count').textContent = data.plans_count + ' Plan(s)';
                document.getElementById('view_registered_at').textContent = data.registered_at;

                loadingEl.classList.add('d-none');
                contentEl.classList.remove('d-none');
            })
            .catch(err => {
                console.error(err);
                loadingEl.innerHTML = '<div class="text-danger small">Failed to load gym details.</div>';
            });
        });
    });
});
</script>
@endpush
