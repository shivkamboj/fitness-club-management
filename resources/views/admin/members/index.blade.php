@extends('layouts.dashboard')

@section('title', 'Member Management')
@section('page_heading', 'Members Directory')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Members Directory</h2>
        <p class="text-muted mb-0 small">Manage gym memberships, assigned plans, and subscription statuses.</p>
    </div>
    <div>
        <button class="btn-gwb-primary" type="button" data-bs-toggle="modal" data-bs-target="#addMemberModal">
            <i class="fa-solid fa-user-plus me-1"></i> Register New Member
        </button>
    </div>
</div>

<div class="gwb-card mb-4">
    <div class="row g-3 align-items-center mb-3">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, email, or phone...">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <select class="form-select bg-dark border-secondary text-white">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="expiring">Expiring Soon</option>
                <option value="expired">Expired</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select class="form-select bg-dark border-secondary text-white">
                <option value="">All Membership Plans</option>
                <option value="annual">Annual Pro Flex</option>
                <option value="monthly">Monthly Elite</option>
                <option value="quarterly">Quarterly Beast</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="gwb-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member Profile</th>
                    <th>Phone</th>
                    <th>Membership Plan</th>
                    <th>Expiration</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-muted">#MB-101</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 34px; height: 34px;">AS</div>
                            <div>
                                <div class="fw-semibold text-white">Aarav Sharma</div>
                                <div class="small text-muted">aarav.sharma@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">+91 98765 43210</td>
                    <td><span class="badge bg-dark text-orange border border-secondary">Annual Pro Flex</span></td>
                    <td class="text-muted">Dec 14, 2026</td>
                    <td><span class="badge-status badge-active">Active</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light me-1" title="View Profile"><i class="fa-solid fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-warning" title="Edit Member"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">#MB-102</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 34px; height: 34px;">PP</div>
                            <div>
                                <div class="fw-semibold text-white">Priya Patel</div>
                                <div class="small text-muted">priya.patel@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">+91 98123 45678</td>
                    <td><span class="badge bg-dark text-orange border border-secondary">Monthly Elite</span></td>
                    <td class="text-muted">Aug 20, 2026</td>
                    <td><span class="badge-status badge-active">Active</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light me-1" title="View Profile"><i class="fa-solid fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-warning" title="Edit Member"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">#MB-103</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width: 34px; height: 34px;">SG</div>
                            <div>
                                <div class="fw-semibold text-white">Sneha Gupta</div>
                                <div class="small text-muted">sneha.g@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-muted">+91 99887 76655</td>
                    <td><span class="badge bg-dark text-orange border border-secondary">Basic Monthly</span></td>
                    <td class="text-muted">Jul 25, 2026</td>
                    <td><span class="badge-status badge-warning">Expiring Soon</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light me-1" title="View Profile"><i class="fa-solid fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-warning" title="Edit Member"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
