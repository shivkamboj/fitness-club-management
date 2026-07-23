@extends('layouts.dashboard')

@section('title', 'SaaS Purchased Subscriptions')
@section('page_heading', 'Purchased Subscriptions')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">SaaS Subscription Purchases</h2>
        <p class="text-muted mb-0 small">Monitor revenue collected from gym owners purchasing SaaS platform plans.</p>
    </div>
</div>

<div class="gwb-card">
    <div class="table-responsive">
        <table class="gwb-table">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Gym Owner / Gym</th>
                    <th>Plan Purchased</th>
                    <th>Billing Cycle</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="mono text-orange">#SAAS-99201</td>
                    <td>
                        <div class="fw-semibold text-white">Vikram Malhotra</div>
                        <div class="small text-muted">Iron Pulse Fitness</div>
                    </td>
                    <td><span class="badge bg-dark text-orange border border-secondary">Enterprise Tier</span></td>
                    <td class="text-muted">Annual</td>
                    <td class="fw-bold text-white">₹49,999</td>
                    <td><span class="badge-status badge-active">Paid</span></td>
                    <td class="text-muted">Jul 01, 2026</td>
                </tr>
                <tr>
                    <td class="mono text-orange">#SAAS-99202</td>
                    <td>
                        <div class="fw-semibold text-white">Sunil Verma</div>
                        <div class="small text-muted">PowerHouse Gym</div>
                    </td>
                    <td><span class="badge bg-dark text-orange border border-secondary">Growth Tier</span></td>
                    <td class="text-muted">Monthly</td>
                    <td class="fw-bold text-white">₹4,999</td>
                    <td><span class="badge-status badge-active">Paid</span></td>
                    <td class="text-muted">Jul 15, 2026</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
