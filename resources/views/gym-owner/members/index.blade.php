@extends('layouts.dashboard')

@section('title', 'Members Management')
@section('page_heading', 'Gym Members Management')

@section('content')

{{-- Header --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Gym Members</h2>
        <p class="text-muted mb-0 small">Add, edit, manage gym members, and dispatch WhatsApp &amp; Mail credentials.</p>
    </div>
    <div>
        <button type="button" class="btn btn-gwb-primary py-2 px-3" data-bs-toggle="modal" data-bs-target="#createMemberModal">
            <i class="fa-solid fa-user-plus me-1"></i> Add New Member
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

{{-- Newly Created Credentials Flash Alert --}}
@if(session('created_credentials'))
    @php $cred = session('created_credentials'); @endphp
    <div class="gwb-card border-success mb-4 p-4" style="background: rgba(34, 197, 94, 0.08); border-color: rgba(34, 197, 94, 0.4) !important;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h4 class="fw-bold text-success fs-5 mb-1"><i class="fa-solid fa-sparkles me-2"></i>Member Created — Login Credentials Generated</h4>
                <div class="text-white small mb-2">Member: <strong>{{ $cred['name'] }}</strong></div>
                <div class="d-flex flex-wrap gap-3 text-muted small font-monospace">
                    <div><span class="text-secondary">Email:</span> <span class="text-white fw-bold">{{ $cred['email'] }}</span></div>
                    <div><span class="text-secondary">Phone:</span> <span class="text-white fw-bold">{{ $cred['phone'] }}</span></div>
                    <div><span class="text-secondary">Password:</span> <span class="text-warning fw-bold fs-6">{{ $cred['password'] }}</span></div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ $cred['whatsapp_url'] }}" target="_blank" class="btn btn-success fw-bold text-white px-3 py-2 text-decoration-none" style="background-color: #25D366; border-color: #25D366;">
                    <i class="fa-brands fa-whatsapp me-1 fs-5 align-middle"></i> Send Credentials on WhatsApp
                </a>
            </div>
        </div>
    </div>
@endif

{{-- Auto-reopen create modal if there are validation errors --}}
@if($errors->any())
    @php $reopenCreateModal = !session('_edit_error'); @endphp
@else
    @php $reopenCreateModal = false; @endphp
@endif

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Total Members</span>
                <div class="rounded-circle p-2 bg-dark border border-secondary text-white">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <h3 class="fw-bold text-white mb-0 fs-3">{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Active Members</span>
                <div class="rounded-circle p-2 bg-dark border border-success text-success">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <h3 class="fw-bold text-success mb-0 fs-3">{{ $stats['active'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Inactive Members</span>
                <div class="rounded-circle p-2 bg-dark border border-secondary text-muted">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
            </div>
            <h3 class="fw-bold text-muted mb-0 fs-3">{{ $stats['inactive'] }}</h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="gwb-card p-3 h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold">Joined This Month</span>
                <div class="rounded-circle p-2 bg-dark border border-primary text-info">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </div>
            <h3 class="fw-bold text-info mb-0 fs-3">{{ $stats['this_month'] }}</h3>
        </div>
    </div>
</div>

{{-- Search & Filters --}}
<div class="gwb-card mb-4 p-3">
    <form method="GET" action="{{ route('gym-owner.members.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control bg-dark border-secondary text-white"
                       placeholder="Search member name, email, phone..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-6 col-md-3 text-end">
            <button type="submit" class="btn btn-sm btn-gwb-primary w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('gym-owner.members.index') }}" class="btn btn-sm btn-gwb-secondary w-100 mt-1"><i class="fa-solid fa-xmark me-1"></i> Clear</a>
            @endif
        </div>
    </form>
</div>

{{-- Members Table --}}
<div class="gwb-card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,.04);">
            <thead>
                <tr class="border-bottom border-secondary">
                    <th class="py-3 px-3 text-muted small fw-semibold">Member</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Contact &amp; WhatsApp</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Gender</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Joining Date</th>
                    <th class="py-3 px-3 text-muted small fw-semibold">Status</th>
                    <th class="py-3 px-3 text-muted small fw-semibold text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $m)
                    <tr class="border-bottom border-secondary">
                        <td class="py-3 px-3">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $initials = strtoupper(substr($m->first_name ?: $m->name, 0, 1) . substr($m->last_name ?: '', 0, 1));
                                @endphp
                                <div class="rounded-circle bg-dark border border-secondary text-orange fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                    {{ $initials ?: 'M' }}
                                </div>
                                <div>
                                    <div class="fw-bold text-white fs-6 mb-0">{{ $m->full_name }}</div>
                                    <div class="text-muted small"><i class="fa-regular fa-envelope me-1"></i>{{ $m->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-white small"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $m->phone }}</span>
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', (string)$m->phone);
                                    if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                                    $waUrl = 'https://wa.me/' . $cleanPhone;
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="btn btn-sm btn-outline-success border-0 px-2 text-decoration-none" title="Chat on WhatsApp">
                                    <i class="fa-brands fa-whatsapp fs-5"></i>
                                </a>
                            </div>
                        </td>
                        <td class="py-3 px-3">
                            <span class="text-capitalize text-muted small">{{ $m->gender ?: '—' }}</span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="small text-white">
                                <i class="fa-regular fa-calendar me-1 text-muted"></i>{{ $m->joining_date ? $m->joining_date->format('M d, Y') : '—' }}
                            </span>
                        </td>
                        <td class="py-3 px-3">
                            @if($m->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-muted border border-secondary py-1 px-2">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-3 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-gwb-secondary btn-sm px-2 edit-member-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editMemberModal"
                                        data-id="{{ $m->id }}"
                                        data-name="{{ $m->full_name }}"
                                        data-email="{{ $m->email }}"
                                        data-phone="{{ $m->phone }}"
                                        data-gender="{{ $m->gender }}"
                                        data-joining="{{ $m->joining_date ? $m->joining_date->format('Y-m-d') : '' }}"
                                        data-status="{{ $m->status }}"
                                        title="Edit Member">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <form action="{{ route('gym-owner.members.destroy', $m->id) }}" method="POST" class="d-inline delete-member-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm px-2 delete-member-btn" title="Delete Member">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <i class="fa-solid fa-users text-muted fs-1 mb-3"></i>
                                <h4 class="fw-bold text-white fs-5 mb-2">No Gym Members Found</h4>
                                <p class="text-muted small mb-4">Start by adding your first gym member to grant access to workout plans and diet routines.</p>
                                <button type="button" class="btn btn-gwb-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createMemberModal">
                                    <i class="fa-solid fa-user-plus me-1"></i> Add Gym Member
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())
        <div class="p-3 border-top border-secondary">
            {{ $members->links() }}
        </div>
    @endif
</div>

{{-- Create Member Modal --}}
<div class="modal fade" id="createMemberModal" tabindex="-1" aria-labelledby="createMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <form action="{{ route('gym-owner.members.store') }}" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white" id="createMemberModalLabel"><i class="fa-solid fa-user-plus me-2 text-orange"></i>Add New Gym Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Inline error summary inside modal --}}
                    @if($errors->any())
                        <div class="alert border-0 mb-3 py-2 px-3 small text-white" style="background:rgba(239,68,68,.15);border-left:4px solid #ef4444!important;">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="create_m_name" class="form-label text-white small fw-semibold">Full Name *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror"
                                   id="create_m_name" name="name" required
                                   placeholder="e.g. Vikram Sharma"
                                   value="{{ old('name') }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_m_email" class="form-label text-white small fw-semibold">Email Address *</label>
                            <input type="email" class="form-control bg-dark border-secondary text-white @error('email') is-invalid @enderror"
                                   id="create_m_email" name="email" required
                                   placeholder="e.g. vikram@example.com"
                                   value="{{ old('email') }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="create_m_phone" class="form-label text-white small fw-semibold">Phone Number *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white @error('phone') is-invalid @enderror"
                                   id="create_m_phone" name="phone" required
                                   placeholder="e.g. +91 9876543210"
                                   value="{{ old('phone') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="create_m_gender" class="form-label text-white small fw-semibold">Gender</label>
                            <select class="form-select bg-dark border-secondary text-white" id="create_m_gender" name="gender">
                                <option value="male"   {{ old('gender','male')   === 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender','male')   === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"  {{ old('gender','male')   === 'other'  ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="create_m_joining" class="form-label text-white small fw-semibold">Joining Date</label>
                            <input type="date" class="form-control bg-dark border-secondary text-white"
                                   id="create_m_joining" name="joining_date"
                                   value="{{ old('joining_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-12">
                            <label for="create_m_password" class="form-label text-white small fw-semibold">Login Password (leave blank to auto-generate)</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-dark border-secondary text-white font-monospace"
                                       id="create_m_password" name="password"
                                       placeholder="e.g. GymPass123"
                                       value="{{ old('password') }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="generateRandomPassword('create_m_password')"><i class="fa-solid fa-key me-1"></i> Auto-Generate</button>
                            </div>
                            <div class="form-text text-muted small mt-1"><i class="fa-solid fa-circle-info me-1"></i>Login credentials will be sent to the member via Email &amp; WhatsApp link.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-gwb-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Save Member &amp; Send Credentials</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Member Modal --}}
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <form id="editMemberForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white" id="editMemberModalLabel"><i class="fa-solid fa-user-pen me-2 text-orange"></i>Edit Gym Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="edit_m_name" class="form-label text-white small fw-semibold">Full Name *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" id="edit_m_name" name="name" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_m_email" class="form-label text-white small fw-semibold">Email Address *</label>
                            <input type="email" class="form-control bg-dark border-secondary text-white" id="edit_m_email" name="email" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_m_phone" class="form-label text-white small fw-semibold">Phone Number *</label>
                            <input type="text" class="form-control bg-dark border-secondary text-white" id="edit_m_phone" name="phone" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="edit_m_gender" class="form-label text-white small fw-semibold">Gender</label>
                            <select class="form-select bg-dark border-secondary text-white" id="edit_m_gender" name="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="edit_m_status" class="form-label text-white small fw-semibold">Status *</label>
                            <select class="form-select bg-dark border-secondary text-white" id="edit_m_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_m_joining" class="form-label text-white small fw-semibold">Joining Date</label>
                            <input type="date" class="form-control bg-dark border-secondary text-white" id="edit_m_joining" name="joining_date">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="edit_m_password" class="form-label text-white small fw-semibold">New Password (optional)</label>
                            <input type="password" class="form-control bg-dark border-secondary text-white" id="edit_m_password" name="password" placeholder="Leave empty to keep current password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-gwb-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gwb-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Update Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function generateRandomPassword(targetId) {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@!";
        let pass = "";
        for (let i = 0; i < 8; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById(targetId).value = pass;
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-reopen create member modal if there are validation errors (e.g. duplicate email)
        @if($reopenCreateModal)
            const createModal = new bootstrap.Modal(document.getElementById('createMemberModal'), { backdrop: 'static' });
            createModal.show();
        @endif

        // Populate edit modal
        const editMemberModal = document.getElementById('editMemberModal');
        if (editMemberModal) {
            editMemberModal.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                const id = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name');
                const email = btn.getAttribute('data-email');
                const phone = btn.getAttribute('data-phone');
                const gender = btn.getAttribute('data-gender');
                const joining = btn.getAttribute('data-joining');
                const status = btn.getAttribute('data-status');

                const form = document.getElementById('editMemberForm');
                form.action = `/gym-owner/members/${id}`;

                document.getElementById('edit_m_name').value = name || '';
                document.getElementById('edit_m_email').value = email || '';
                document.getElementById('edit_m_phone').value = phone || '';
                document.getElementById('edit_m_gender').value = gender || 'male';
                document.getElementById('edit_m_joining').value = joining || '';
                document.getElementById('edit_m_status').value = status || 'active';
                document.getElementById('edit_m_password').value = '';
            });
        }

        // Delete member with SweetAlert2
        document.querySelectorAll('.delete-member-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('.delete-member-form');
                const isDark = document.documentElement.getAttribute('data-theme') !== 'light';

                Swal.fire({
                    title: 'Delete Gym Member?',
                    text: 'Are you sure? This member account will be deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c',
                    cancelButtonColor: '#4b5563',
                    confirmButtonText: 'Yes, delete member!',
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
