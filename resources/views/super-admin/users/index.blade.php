@extends('layouts.dashboard')

@section('title', 'Platform User Directory')
@section('page_heading', 'User Directory')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">System Users Directory</h2>
        <p class="text-muted mb-0 small">Overview of all platform accounts: Super Admins, Gym Owners, Staff, Trainers, and Members.</p>
    </div>
</div>

<div class="gwb-card">
    <div class="table-responsive">
        <table class="gwb-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Associated Gym</th>
                    <th>Joined Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-semibold text-white">Super Administrator</td>
                    <td class="text-muted">admin@gymplatform.com</td>
                    <td><span class="badge bg-danger">super-admin</span></td>
                    <td class="text-muted">Global Platform</td>
                    <td class="text-muted">Jan 01, 2026</td>
                </tr>
                <tr>
                    <td class="fw-semibold text-white">Vikram Malhotra</td>
                    <td class="text-muted">vikram@ironpulse.com</td>
                    <td><span class="badge bg-warning text-dark">gym-owner</span></td>
                    <td class="text-orange">Iron Pulse Fitness</td>
                    <td class="text-muted">Feb 15, 2026</td>
                </tr>
                <tr>
                    <td class="fw-semibold text-white">Rahul Kapoor</td>
                    <td class="text-muted">rahul.trainer@ironpulse.com</td>
                    <td><span class="badge bg-info text-dark">trainer</span></td>
                    <td class="text-orange">Iron Pulse Fitness</td>
                    <td class="text-muted">Mar 10, 2026</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
