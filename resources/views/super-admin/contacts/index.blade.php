@extends('layouts.dashboard')

@section('title', 'Contact Requests & Leads')
@section('page_heading', 'Contact Requests')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Contact Form Inquiries & Sales Leads</h2>
        <p class="text-muted mb-0 small">Review and follow up on gym software sales inquiries submitted on the public landing page.</p>
    </div>
</div>

<div class="gwb-card">
    <div class="table-responsive">
        <table class="gwb-table">
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Email & Phone</th>
                    <th>Gym Name / Details</th>
                    <th>Message Snippet</th>
                    <th>Date Received</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-semibold text-white">Rajesh Khanna</td>
                    <td>
                        <div class="text-white">rajesh@gymchain.in</div>
                        <div class="small text-muted">+91 98111 22233</div>
                    </td>
                    <td class="text-orange">Olympus Gyms (3 Branches)</td>
                    <td class="text-muted">Interested in multi-branch enterprise setup pricing...</td>
                    <td class="text-muted">Today, 12:30 PM</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-gwb-primary"><i class="fa-solid fa-reply me-1"></i> Contact Lead</button>
                    </td>
                </tr>
                <tr>
                    <td class="fw-semibold text-white">Aniket Roy</td>
                    <td>
                        <div class="text-white">aniket@fitlife.com</div>
                        <div class="small text-muted">+91 99887 11223</div>
                    </td>
                    <td class="text-orange">FitLife Studio</td>
                    <td class="text-muted">Want a demo for custom mobile app branding...</td>
                    <td class="text-muted">Yesterday, 4:15 PM</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-gwb-primary"><i class="fa-solid fa-reply me-1"></i> Contact Lead</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
