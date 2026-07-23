@extends('layouts.dashboard')

@section('title', 'Gym Owners Directory')
@section('page_heading', 'Gym Owners Management')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Gym Owners & Registered Centers</h2>
        <p class="text-muted mb-0 small">Approve new gym signups, manage gym accounts, and toggle tenant statuses.</p>
    </div>
    <div>
        <button class="btn-gwb-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Gym Owner
        </button>
    </div>
</div>

<div class="gwb-card">
    <div class="table-responsive">
        <table class="gwb-table">
            <thead>
                <tr>
                    <th>Gym Name</th>
                    <th>Owner Details</th>
                    <th>Location</th>
                    <th>Active Plan</th>
                    <th>Members</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-semibold text-white">
                        <i class="fa-solid fa-dumbbell text-orange me-2"></i> Iron Pulse Fitness
                    </td>
                    <td>
                        <div class="fw-medium text-white">Vikram Malhotra</div>
                        <div class="small text-muted">vikram@ironpulse.com</div>
                    </td>
                    <td class="text-muted">Mumbai, MH</td>
                    <td><span class="badge bg-dark text-orange border border-secondary">Enterprise Tier</span></td>
                    <td class="fw-bold text-white">248</td>
                    <td><span class="badge-status badge-active">Active</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light me-1"><i class="fa-solid fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
                <tr>
                    <td class="fw-semibold text-white">
                        <i class="fa-solid fa-dumbbell text-orange me-2"></i> PowerHouse Gym Center
                    </td>
                    <td>
                        <div class="fw-medium text-white">Sunil Verma</div>
                        <div class="small text-muted">sunil@powerhouse.com</div>
                    </td>
                    <td class="text-muted">Delhi, DL</td>
                    <td><span class="badge bg-dark text-orange border border-secondary">Growth Tier</span></td>
                    <td class="fw-bold text-white">180</td>
                    <td><span class="badge-status badge-active">Active</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light me-1"><i class="fa-solid fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
