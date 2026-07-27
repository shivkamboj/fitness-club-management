@extends('layouts.dashboard')

@section('title', 'Leads & Enquiries')
@section('page_heading', 'Leads & Enquiries Management')

@section('content')

{{-- Header section --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Leads &amp; Enquiries</h2>
        <p class="text-muted mb-0 small">Track prospective members, follow-ups, lead sources, and conversions.</p>
    </div>
    <div>
        <button type="button" class="btn btn-gwb-primary py-2 px-3" data-bs-toggle="modal" data-bs-target="#createLeadModal">
            <i class="fa-solid fa-plus me-1"></i> Add Lead / Enquiry
        </button>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-dismissible fade show mb-4 border-0 text-white" style="background: rgba(34,197,94,.15); border-left: 4px solid #22c55e !important;" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-dismissible fade show mb-4 border-0 text-white" style="background: rgba(239,68,68,.15); border-left: 4px solid #ef4444 !important;" role="alert">
        <i class="fa-solid fa-circle-xmark me-2"></i>Please check the form for errors.
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Summary Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total Leads</span>
                <div class="rounded-circle p-2 bg-dark border border-secondary text-white">
                    <i class="fa-solid fa-users-line"></i>
                </div>
            </div>
            <h3 class="fw-bold text-white mb-0 fs-3">{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">New Enquiries</span>
                <div class="rounded-circle p-2 bg-dark border border-primary text-info">
                    <i class="fa-solid fa-sparkles"></i>
                </div>
            </div>
            <h3 class="fw-bold text-info mb-0 fs-3">{{ $stats['new'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Follow Up Needed</span>
                <div class="rounded-circle p-2 bg-dark border border-warning text-warning">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
            </div>
            <h3 class="fw-bold text-warning mb-0 fs-3">{{ $stats['follow_up'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Converted Members</span>
                <div class="rounded-circle p-2 bg-dark border border-success text-success">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <h3 class="fw-bold text-success mb-0 fs-3">{{ $stats['converted'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-2-4 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Lost Enquiries</span>
                <div class="rounded-circle p-2 bg-dark border border-danger text-danger">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
            </div>
            <h3 class="fw-bold text-danger mb-0 fs-3">{{ $stats['lost'] }}</h3>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="gwb-card mb-4 p-3">
    <form method="GET" action="{{ route('gym-owner.leads.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control bg-dark border-secondary text-white" 
                       placeholder="Search lead name, phone, email..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="source" class="form-select form-select-sm bg-dark border-secondary text-white" onchange="this.form.submit()">
                <option value="">All Sources</option>
                @foreach($sources as $src)
                    <option value="{{ $src }}" {{ request('source') === $src ? 'selected' : '' }}>{{ $src }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2 text-end">
            <button type="submit" class="btn btn-sm btn-gwb-primary w-100 mb-1 mb-md-0"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            @if(request('search') || request('source') || request('status'))
                <a href="{{ route('gym-owner.leads.index') }}" class="btn btn-sm btn-gwb-secondary w-100 mt-1"><i class="fa-solid fa-xmark me-1"></i> Clear</a>
            @endif
        </div>
    </form>
</div>

{{-- Leads Table --}}
<div class="gwb-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,.04);">
            <thead>
                <tr class="border-bottom border-secondary">
                    <th class="py-3 px-3 text-muted small fw-semibold">Lead Info</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Contact / Connect</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Source</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Status</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Follow Up Date</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Assigned Trainer</th>
                    <th class="py-3 px-3 text-muted small fw-semibold text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr class="border-bottom border-secondary">
                        <td class="py-3 px-3">
                            <div class="fw-bold text-white fs-6 mb-0">{{ $lead->name }}</div>
                            @if($lead->notes)
                                <div class="text-muted small text-truncate" style="max-width: 220px;" title="{{ $lead->notes }}">
                                    <i class="fa-regular fa-sticky-note me-1"></i>{{ $lead->notes }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <div class="text-white small fw-semibold"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $lead->phone }}</div>
                                    @if($lead->email)
                                        <div class="text-muted small"><i class="fa-regular fa-envelope me-1"></i>{{ $lead->email }}</div>
                                    @endif
                                </div>
                                <a href="{{ $lead->whatsapp_url }}" target="_blank" class="btn btn-sm btn-outline-success border-0 px-2 text-decoration-none" title="Chat on WhatsApp">
                                    <i class="fa-brands fa-whatsapp fs-5"></i>
                                </a>
                            </div>
                        </td>
                        <td class="py-3 px-3">
                            @php
                                $sourceBadges = [
                                    'Walk In'       => ['bg' => 'rgba(16, 185, 129, 0.15)', 'color' => '#10b981', 'icon' => 'fa-person-walking'],
                                    'Website Lead'  => ['bg' => 'rgba(59, 130, 246, 0.15)', 'color' => '#3b82f6', 'icon' => 'fa-globe'],
                                    'Facebook Lead' => ['bg' => 'rgba(99, 102, 241, 0.15)', 'color' => '#818cf8', 'icon' => 'fa-facebook-f'],
                                    'Instagram Lead'=> ['bg' => 'rgba(236, 72, 153, 0.15)', 'color' => '#f472b6', 'icon' => 'fa-instagram'],
                                    'Phone Call'    => ['bg' => 'rgba(245, 158, 11, 0.15)', 'color' => '#f59e0b', 'icon' => 'fa-phone-flip'],
                                ];
                                $sb = $sourceBadges[$lead->source] ?? ['bg' => 'rgba(156, 163, 175, 0.15)', 'color' => '#9ca3af', 'icon' => 'fa-circle-info'];
                            @endphp
                            <span class="badge py-1 px-2 border" style="background: {{ $sb['bg'] }}; color: {{ $sb['color'] }}; border-color: {{ $sb['color'] }}33 !important;">
                                <i class="fa-solid {{ $sb['icon'] }} me-1"></i> {{ $lead->source }}
                            </span>
                        </td>
                        <td class="py-3 px-3">
                            <form action="{{ route('gym-owner.leads.status', $lead->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm border-secondary text-white py-1 px-2 fw-semibold" 
                                        style="width: 130px; background-color: var(--gwb-surface-2);" 
                                        onchange="this.form.submit()">
                                    <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>🔵 New</option>
                                    <option value="Follow Up" {{ $lead->status === 'Follow Up' ? 'selected' : '' }}>🟠 Follow Up</option>
                                    <option value="Converted" {{ $lead->status === 'Converted' ? 'selected' : '' }}>🟢 Converted</option>
                                    <option value="Lost" {{ $lead->status === 'Lost' ? 'selected' : '' }}>🔴 Lost</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-3 px-3">
                            @if($lead->follow_up_date)
                                <span class="small {{ $lead->follow_up_date->isPast() && $lead->status !== 'Converted' ? 'text-danger fw-bold' : 'text-white' }}">
                                    <i class="fa-regular fa-calendar me-1"></i>{{ $lead->follow_up_date->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            @if($lead->assignedTrainer)
                                <span class="small text-white"><i class="fa-solid fa-user-ninja me-1 text-orange"></i>{{ $lead->assignedTrainer->full_name }}</span>
                            @else
                                <span class="text-muted small">Unassigned</span>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-gwb-secondary btn-sm px-2 edit-lead-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editLeadModal"
                                        data-lead-id="{{ $lead->id }}"
                                        data-lead-name="{{ $lead->name }}"
                                        data-lead-phone="{{ $lead->phone }}"
                                        data-lead-email="{{ $lead->email }}"
                                        data-lead-source="{{ $lead->source }}"
                                        data-lead-status="{{ $lead->status }}"
                                        data-lead-followup="{{ $lead->follow_up_date ? $lead->follow_up_date->format('Y-m-d') : '' }}"
                                        data-lead-notes="{{ $lead->notes }}"
                                        data-lead-assigned="{{ $lead->assigned_to }}"
                                        title="Edit Lead">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form action="{{ route('gym-owner.leads.destroy', $lead->id) }}" method="POST" class="d-inline delete-lead-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm px-2 delete-lead-btn" title="Delete Lead">
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
                                <i class="fa-solid fa-headset text-muted fs-1 mb-3"></i>
                                <h4 class="fw-bold text-white fs-5 mb-2">No Leads Found</h4>
                                <p class="text-muted small mb-4">Start capturing customer enquiries to grow your membership.</p>
                                <button type="button" class="btn btn-gwb-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                                    <i class="fa-solid fa-plus me-1"></i> Add Lead Enquiry
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leads->hasPages())
        <div class="p-3 border-top border-secondary">
            {{ $leads->links() }}
        </div>
    @endif
</div>

{{-- Create Lead Modal --}}
<div class="modal fade" id="createLeadModal" tabindex="-1" aria-labelledby="createLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <form action="{{ route('gym-owner.leads.store') }}" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white" id="createLeadModalLabel"><i class="fa-solid fa-user-plus me-2 text-orange"></i>Add New Lead / Enquiry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="create_name" class="form-label text-white small fw-semibold">Full Name *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" id="create_name" name="name" required placeholder="e.g. Rahul Sharma">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_phone" class="form-label text-white small fw-semibold">Phone Number *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" id="create_phone" name="phone" required placeholder="e.g. +91 9876543210">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_email" class="form-label text-white small fw-semibold">Email Address</label>
                            <input type="email" class="form-control bg-dark border-secondary text-white" id="create_email" name="email" placeholder="e.g. rahul@example.com">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="create_source" class="form-label text-white small fw-semibold">Lead Source *</label>
                            <select class="form-select bg-dark border-secondary text-white" id="create_source" name="source" required>
                                @foreach($sources as $source)
                                    <option value="{{ $source }}">{{ $source }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="create_status" class="form-label text-white small fw-semibold">Initial Status *</label>
                            <select class="form-select bg-dark border-secondary text-white" id="create_status" name="status" required>
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_followup" class="form-label text-white small fw-semibold">Follow Up Date</label>
                            <input type="date" class="form-control bg-dark border-secondary text-white" id="create_followup" name="follow_up_date">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_assigned" class="form-label text-white small fw-semibold">Assign to Trainer</label>
                            <select class="form-select bg-dark border-secondary text-white" id="create_assigned" name="assigned_to">
                                <option value="">— Unassigned —</option>
                                @foreach($trainers as $tr)
                                    <option value="{{ $tr->id }}">{{ $tr->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="create_notes" class="form-label text-white small fw-semibold">Enquiry Notes / Requirements</label>
                            <textarea class="form-control bg-dark border-secondary text-white" id="create_notes" name="notes" rows="3" placeholder="Interested in personal training, weight loss package, gym timings..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-gwb-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Lead Modal --}}
<div class="modal fade" id="editLeadModal" tabindex="-1" aria-labelledby="editLeadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <form id="editLeadForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white" id="editLeadModalLabel"><i class="fa-solid fa-user-pen me-2 text-orange"></i>Edit Lead Enquiry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="edit_name" class="form-label text-white small fw-semibold">Full Name *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" id="edit_name" name="name" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_phone" class="form-label text-white small fw-semibold">Phone Number *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" id="edit_phone" name="phone" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_email" class="form-label text-white small fw-semibold">Email Address</label>
                            <input type="email" class="form-control bg-dark border-secondary text-white" id="edit_email" name="email">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="edit_source" class="form-label text-white small fw-semibold">Lead Source *</label>
                            <select class="form-select bg-dark border-secondary text-white" id="edit_source" name="source" required>
                                @foreach($sources as $source)
                                    <option value="{{ $source }}">{{ $source }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="edit_status" class="form-label text-white small fw-semibold">Status *</label>
                            <select class="form-select bg-dark border-secondary text-white" id="edit_status" name="status" required>
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}">{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_followup" class="form-label text-white small fw-semibold">Follow Up Date</label>
                            <input type="date" class="form-control bg-dark border-secondary text-white" id="edit_followup" name="follow_up_date">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_assigned" class="form-label text-white small fw-semibold">Assign to Trainer</label>
                            <select class="form-select bg-dark border-secondary text-white" id="edit_assigned" name="assigned_to">
                                <option value="">— Unassigned —</option>
                                @foreach($trainers as $tr)
                                    <option value="{{ $tr->id }}">{{ $tr->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_notes" class="form-label text-white small fw-semibold">Notes</label>
                            <textarea class="form-control bg-dark border-secondary text-white" id="edit_notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-gwb-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Update Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Edit lead modal data populated
        const editLeadModal = document.getElementById('editLeadModal');
        if (editLeadModal) {
            editLeadModal.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                const id = btn.getAttribute('data-lead-id');
                const name = btn.getAttribute('data-lead-name');
                const phone = btn.getAttribute('data-lead-phone');
                const email = btn.getAttribute('data-lead-email');
                const source = btn.getAttribute('data-lead-source');
                const status = btn.getAttribute('data-lead-status');
                const followup = btn.getAttribute('data-lead-followup');
                const notes = btn.getAttribute('data-lead-notes');
                const assigned = btn.getAttribute('data-lead-assigned');

                const form = document.getElementById('editLeadForm');
                form.action = `/gym-owner/leads/${id}`;

                document.getElementById('edit_name').value = name || '';
                document.getElementById('edit_phone').value = phone || '';
                document.getElementById('edit_email').value = email || '';
                document.getElementById('edit_source').value = source || 'Walk In';
                document.getElementById('edit_status').value = status || 'New';
                document.getElementById('edit_followup').value = followup || '';
                document.getElementById('edit_notes').value = notes || '';
                document.getElementById('edit_assigned').value = assigned || '';
            });
        }

        // Delete lead with SweetAlert2
        document.querySelectorAll('.delete-lead-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('.delete-lead-form');
                const isDark = document.documentElement.getAttribute('data-theme') !== 'light';

                Swal.fire({
                    title: 'Delete Lead Enquiry?',
                    text: 'Are you sure? This lead record will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c',
                    cancelButtonColor: '#4b5563',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    background: isDark ? 'var(--gwb-surface-1, #1e1e1e)' : '#ffffff',
                    color: isDark ? '#ffffff' : '#1e1e1e',
                    customClass: {
                        popup: isDark ? 'border border-secondary rounded-3' : 'border border-light-subtle rounded-3 shadow',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
