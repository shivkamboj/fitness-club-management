@extends('layouts.dashboard')

@section('title', 'Payments & Transactions')
@section('page_heading', 'Payments & Billing')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Payments & Transactions</h2>
        <p class="text-muted mb-0 small">View all recent revenue, Razorpay/Stripe transactions, and invoice receipts.</p>
    </div>
    <div>
        <button class="btn-gwb-primary">
            <i class="fa-solid fa-plus me-1"></i> Record Manual Payment
        </button>
    </div>
</div>

<div class="gwb-card">
    <div class="table-responsive">
        <table class="gwb-table">
            <thead>
                <tr>
                    <th>Txn ID</th>
                    <th>Member</th>
                    <th>Plan Purchased</th>
                    <th>Amount Paid</th>
                    <th>Gateway</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th class="text-end">Invoice</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="mono text-orange">#TXN-884920</td>
                    <td class="fw-semibold text-white">Aarav Sharma</td>
                    <td class="text-muted">Annual Pro Flex</td>
                    <td class="fw-bold text-white">₹19,999</td>
                    <td><span class="badge bg-dark border border-secondary text-muted">Razorpay</span></td>
                    <td class="text-muted">Jul 23, 2026 • 14:22</td>
                    <td><span class="badge-status badge-active">Success</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light"><i class="fa-solid fa-file-pdf me-1"></i> PDF</button>
                    </td>
                </tr>
                <tr>
                    <td class="mono text-orange">#TXN-884919</td>
                    <td class="fw-semibold text-white">Priya Patel</td>
                    <td class="text-muted">Monthly Elite</td>
                    <td class="fw-bold text-white">₹2,499</td>
                    <td><span class="badge bg-dark border border-secondary text-muted">Razorpay</span></td>
                    <td class="text-muted">Jul 22, 2026 • 09:15</td>
                    <td><span class="badge-status badge-active">Success</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light"><i class="fa-solid fa-file-pdf me-1"></i> PDF</button>
                    </td>
                </tr>
                <tr>
                    <td class="mono text-orange">#TXN-884918</td>
                    <td class="fw-semibold text-white">Rohan Verma</td>
                    <td class="text-muted">Quarterly Beast</td>
                    <td class="fw-bold text-white">₹6,999</td>
                    <td><span class="badge bg-dark border border-secondary text-muted">Cash / Manual</span></td>
                    <td class="text-muted">Jul 20, 2026 • 18:40</td>
                    <td><span class="badge-status badge-active">Success</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light"><i class="fa-solid fa-file-pdf me-1"></i> PDF</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
