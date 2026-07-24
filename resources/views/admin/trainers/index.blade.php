@extends('layouts.dashboard')

@section('title', 'Trainer Management')
@section('page_heading', 'Trainer Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.32.2/dist/tagify.css">
<style>
    .trainer-table-wrap { position: relative; min-height: 220px; }
    .trainer-loading-overlay {
        position: absolute; inset: 0; display: none; align-items: center; justify-content: center;
        background: rgba(0,0,0,.35); backdrop-filter: blur(2px); z-index: 5; border-radius: 12px;
    }
    .trainer-loading-overlay.is-active { display: flex; }
    .trainer-avatar-thumb {
        width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
        background: #1f2937; display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .8rem; color: #ff8a3d;
    }
    .image-upload-box {
        border: 1px dashed rgba(255,255,255,.2); border-radius: 12px; padding: 16px;
        text-align: center; cursor: pointer; transition: border-color .2s, background .2s;
        background: rgba(255,255,255,.02);
    }
    .image-upload-box:hover, .image-upload-box.is-dragover {
        border-color: #ff8a3d; background: rgba(255,138,61,.06);
    }
    .image-preview-wrap { position: relative; display: none; margin-top: 12px; }
    .image-preview-wrap.is-visible { display: block; }
    .image-preview-wrap img {
        width: 100%; max-height: 160px; object-fit: cover; border-radius: 10px;
    }
    .image-preview-wrap.profile img {
        width: 96px; height: 96px; border-radius: 50%; object-fit: cover; margin: 0 auto; display: block;
    }
    .btn-remove-image {
        position: absolute; top: 8px; right: 8px; border: 0; border-radius: 50%;
        width: 30px; height: 30px; background: rgba(220,38,38,.9); color: #fff;
    }
    .field-error { color: #f87171; font-size: .8rem; margin-top: .25rem; display: none; }
    .field-error.is-visible { display: block; }
    .is-invalid-field { border-color: #f87171 !important; }
    .modal-gwb .modal-content {
        background: #111827; color: #e5e7eb; border: 1px solid rgba(255,255,255,.08);
    }
    /* Keep header/footer fixed; only body scrolls (form is .modal-content) */
    #trainerFormModal .modal-dialog {
        max-height: calc(100vh - 2rem);
        margin: 1rem auto;
    }
    #trainerFormModal .modal-dialog-scrollable .modal-content {
        max-height: calc(100vh - 2rem);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    #trainerFormModal .modal-body {
        overflow-y: auto !important;
        overscroll-behavior: contain;
        flex: 1 1 auto;
        min-height: 0;
        -webkit-overflow-scrolling: touch;
    }
    #trainerFormModal .modal-header,
    #trainerFormModal .modal-footer {
        flex-shrink: 0;
    }
    .modal-gwb .modal-header, .modal-gwb .modal-footer {
        border-color: rgba(255,255,255,.08);
    }
    .modal-gwb .form-control, .modal-gwb .form-select {
        background: #0b1220; border-color: rgba(255,255,255,.12); color: #fff;
    }
    .modal-gwb .form-control:focus, .modal-gwb .form-select:focus {
        background: #0b1220; color: #fff; border-color: #ff8a3d; box-shadow: 0 0 0 .2rem rgba(255,138,61,.15);
    }
    .modal-gwb .form-label { color: #cbd5e1; font-size: .85rem; }
    .credentials-box {
        background: rgba(255,138,61,.08); border: 1px solid rgba(255,138,61,.25);
        border-radius: 12px; padding: 14px;
    }
    .btn-loading .btn-spinner { display: inline-block; }
    .btn-spinner { display: none; }
    .pagination-gwb .page-link {
        background: #111827; border-color: rgba(255,255,255,.1); color: #e5e7eb;
    }
    .pagination-gwb .page-item.active .page-link {
        background: #ff8a3d; border-color: #ff8a3d; color: #111;
    }
    .pagination-gwb .page-link:hover { background: #1f2937; color: #fff; }
    [data-theme="light"] .modal-gwb .modal-content { background: #fff; color: #111; }
    [data-theme="light"] .modal-gwb .form-control,
    [data-theme="light"] .modal-gwb .form-select { background: #fff; color: #111; }
    [data-theme="light"] .image-upload-box { background: #f8fafc; border-color: #cbd5e1; }

    /* Tagify — match dashboard modal theme */
    .tagify {
        --tags-border-color: rgba(255,255,255,.12);
        --tags-hover-border-color: #ff8a3d;
        --tags-focus-border-color: #ff8a3d;
        --tag-bg: rgba(255,138,61,.18);
        --tag-hover: rgba(255,138,61,.28);
        --tag-text-color: #fff;
        --tag-text-color--edit: #111;
        --tag-remove-bg: rgba(220,38,38,.85);
        --tag-remove-btn-color: #fff;
        --tag-pad: 0.3em 0.5em;
        background: #0b1220;
        color: #fff;
        border-radius: 0.375rem;
        min-height: 42px;
    }
    .tagify:hover, .tagify.tagify--focus {
        border-color: #ff8a3d;
    }
    .tagify__input {
        color: #e5e7eb;
        min-width: 120px;
    }
    .tagify__input::before {
        color: #6b7280;
    }
    .tagify__dropdown {
        background: #111827;
        border-color: rgba(255,255,255,.12);
        color: #e5e7eb;
    }
    .tagify__dropdown__item--active {
        background: rgba(255,138,61,.2);
    }
    .tagify.tagify--invalid {
        border-color: #f87171;
    }
    .spec-tag {
        display: inline-block;
        background: rgba(255,138,61,.15);
        color: #ff8a3d;
        border: 1px solid rgba(255,138,61,.35);
        border-radius: 999px;
        padding: 2px 8px;
        font-size: .72rem;
        margin: 1px 2px;
        white-space: nowrap;
    }
    [data-theme="light"] .tagify {
        --tags-border-color: #cbd5e1;
        --tag-bg: #fff7ed;
        --tag-hover: #ffedd5;
        --tag-text-color: #9a3412;
        background: #fff;
        color: #111;
    }
    [data-theme="light"] .tagify__input { color: #111; }
    [data-theme="light"] .tagify__dropdown {
        background: #fff;
        border-color: #e5e7eb;
        color: #111;
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Trainers Directory</h2>
        <p class="text-muted mb-0 small">Add, edit, and manage trainer accounts for your gym.</p>
    </div>
    <div>
        <button type="button" class="btn-gwb-primary" id="btnOpenAddTrainer">
            <i class="fa-solid fa-user-plus me-1"></i> Add Trainer
        </button>
    </div>
</div>

<div class="gwb-card mb-4">
    <div class="row g-3 align-items-center mb-3">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="trainerSearch" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, email, phone, specialization..." autocomplete="off">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <select id="trainerStatusFilter" class="form-select bg-dark border-secondary text-white">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select id="trainerPerPage" class="form-select bg-dark border-secondary text-white">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
            </select>
        </div>
    </div>

    <div class="trainer-table-wrap">
        <div class="trainer-loading-overlay" id="trainerLoadingOverlay">
            <div class="text-center text-white">
                <div class="spinner-border text-warning" role="status"></div>
                <div class="small mt-2">Loading trainers…</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="gwb-table" id="trainersTable">
                <thead>
                    <tr>
                        <th>Trainer</th>
                        <th>Phone</th>
                        <th>Specialization</th>
                        <th>Experience</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="trainersTableBody">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Loading…</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
        <div class="small text-muted" id="trainersMeta">Showing 0 trainers</div>
        <nav>
            <ul class="pagination pagination-sm pagination-gwb mb-0" id="trainersPagination"></ul>
        </nav>
    </div>
</div>

@include('admin.trainers.partials.modals')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.32.2/dist/tagify.min.js"></script>
<script>
    window.TrainerModule = {
        routes: {
            index: @json(route('gym-owner.trainers.index')),
            store: @json(route('gym-owner.trainers.store')),
            generatePassword: @json(route('gym-owner.trainers.generate-password')),
            show: @json(url('/gym-owner/trainers')),
            update: @json(url('/gym-owner/trainers')),
            destroy: @json(url('/gym-owner/trainers')),
            status: @json(url('/gym-owner/trainers')),
        },
        csrf: @json(csrf_token()),
        specializationWhitelist: [
            'Strength Training',
            'Bodybuilding',
            'Powerlifting',
            'Weight Loss',
            'Yoga',
            'Pilates',
            'HIIT',
            'CrossFit',
            'Cardio',
            'Functional Training',
            'Nutrition',
            'Sports Conditioning',
            'Rehabilitation',
            'Martial Arts',
            'Zumba',
        ],
    };
</script>
<script src="{{ asset('js/trainers.js') }}"></script>
@endpush
