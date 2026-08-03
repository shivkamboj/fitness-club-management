@extends('layouts.dashboard')

@section('title', 'Platform User Directory')
@section('page_heading', 'User Directory')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">System Users Directory</h2>
        <p class="text-muted mb-0 small">Overview and management of all platform accounts across all gym centers.</p>
    </div>
</div>

<!-- Dynamic Summary Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Total Accounts</span>
                    <h3 class="fw-bold text-white mb-0 mt-1">{{ number_format($stats['total']) }}</h3>
                    <span class="small text-muted">Platform-wide</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-orange border border-secondary">
                    <i class="fa-solid fa-users-gear fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Gym Owners</span>
                    <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($stats['gym_owners']) }}</h3>
                    <span class="small text-muted">Tenants</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-warning border border-secondary">
                    <i class="fa-solid fa-building-user fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Trainers</span>
                    <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($stats['trainers']) }}</h3>
                    <span class="small text-muted">Fitness Instructors</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-info border border-secondary">
                    <i class="fa-solid fa-user-ninja fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="gwb-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wider">Members</span>
                    <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($stats['members']) }}</h3>
                    <span class="small text-muted">Gym Members</span>
                </div>
                <div class="rounded-3 p-3 bg-dark text-success border border-secondary">
                    <i class="fa-solid fa-user-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="gwb-card mb-4 p-3">
    <form method="GET" action="{{ route('super-admin.users.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-dark text-muted border-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search name, email, phone..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-12 col-sm-4 col-md-3">
            <select name="role" class="form-select bg-dark text-white border-secondary">
                <option value="">All Roles</option>
                <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Super Admin</option>
                <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Gym Owner</option>
                <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>Staff</option>
                <option value="4" {{ request('role') == '4' ? 'selected' : '' }}>Trainer</option>
                <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
            </select>
        </div>

        <div class="col-12 col-sm-4 col-md-2">
            <select name="status" class="form-select bg-dark text-white border-secondary">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-12 col-sm-4 col-md-2">
            <select name="per_page" class="form-select bg-dark text-white border-secondary" onchange="this.form.submit()">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
            </select>
        </div>

        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn-gwb-primary flex-grow-1">
                <i class="fa-solid fa-filter me-1"></i> Filter
            </button>
            @if(request()->filled('search') || request()->filled('role') || request()->filled('status') || request()->filled('per_page'))
                <a href="{{ route('super-admin.users.index') }}" class="btn btn-outline-secondary d-flex align-items-center px-3" title="Clear Filters">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table Card -->
<div class="gwb-card">
    <div class="table-responsive">
        <table class="gwb-table align-middle">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email Contact</th>
                    <th>Role</th>
                    <th>Associated Gym Center</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-dark text-orange border border-secondary d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; min-width: 36px;">
                                {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold text-white">{{ $user->full_name ?: $user->name }}</div>
                                @if($user->phone)
                                    <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $user->phone }}</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="text-muted"><i class="fa-regular fa-envelope me-1"></i>{{ $user->email }}</div>
                    </td>

                    <td>
                        @php
                            $roleInt = (int) $user->role;
                        @endphp
                        @if($roleInt === \App\Models\User::ROLE_SUPER_ADMIN)
                            <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-shield-halved me-1"></i>Super Admin</span>
                        @elseif($roleInt === \App\Models\User::ROLE_SUPER_ADMIN || $roleInt === \App\Models\User::ROLE_GYM_OWNER)
                            <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-building-user me-1"></i>Gym Owner</span>
                        @elseif($roleInt === \App\Models\User::ROLE_TRAINER)
                            <span class="badge bg-info text-dark px-2 py-1"><i class="fa-solid fa-user-ninja me-1"></i>Trainer</span>
                        @elseif($roleInt === \App\Models\User::ROLE_STAFF)
                            <span class="badge bg-secondary text-white px-2 py-1"><i class="fa-solid fa-user-tie me-1"></i>Staff</span>
                        @else
                            <span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-user me-1"></i>Member</span>
                        @endif
                    </td>

                    <td>
                        @if($roleInt === \App\Models\User::ROLE_SUPER_ADMIN)
                            <span class="badge bg-dark text-muted border border-secondary"><i class="fa-solid fa-globe me-1"></i>Global Platform</span>
                        @elseif($roleInt === \App\Models\User::ROLE_GYM_OWNER)
                            <span class="fw-semibold text-orange"><i class="fa-solid fa-dumbbell me-1"></i>{{ $user->gym_name ?: ($user->full_name . ' Gym') }}</span>
                        @elseif($user->gymOwner)
                            <span class="text-white"><i class="fa-solid fa-building me-1 text-orange"></i>{{ $user->gymOwner->gym_name ?: ($user->gymOwner->full_name . ' Gym') }}</span>
                        @elseif($user->gym_name)
                            <span class="text-white"><i class="fa-solid fa-building me-1 text-orange"></i>{{ $user->gym_name }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td>
                        <form method="POST" action="{{ route('super-admin.users.toggle-status', $user->id) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm p-0 border-0 background-none" title="Click to toggle status" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                @if(($user->status ?? 'active') === 'active')
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

                    <td class="text-muted small">
                        {{ $user->created_at?->format('M d, Y') ?? 'N/A' }}
                    </td>

                    <td class="text-end">
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('super-admin.users.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to remove user {{ $user->full_name ?: $user->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="badge bg-dark text-muted border border-secondary small">You</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <i class="fa-solid fa-users-slash fs-1 text-muted mb-3 opacity-50"></i>
                            <h5 class="text-white fw-bold">No Users Found</h5>
                            <p class="text-muted mb-3 small">No platform users matched your current filter criteria.</p>
                            @if(request()->filled('search') || request()->filled('role') || request()->filled('status'))
                                <a href="{{ route('super-admin.users.index') }}" class="btn-gwb-secondary btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-3 border-top border-secondary opacity-75">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
