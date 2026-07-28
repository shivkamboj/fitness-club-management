@extends('layouts.dashboard')

@section('title', 'Notifications')
@section('page_heading', 'Notifications')

@section('content')
<div class="gwb-card">
    <div class="gwb-card-header">
        <div class="gwb-card-title">
            <i class="fa-solid fa-bell me-2"></i> In-App Notifications
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn-gwb-secondary btn-sm" id="notificationsMarkAllReadBtn">
                <i class="fa-solid fa-check-double me-1"></i> Mark All as Read
            </button>
        </div>
    </div>

    <div id="notificationsAllLoading" class="text-center py-4">
        <div class="spinner-border text-warning" role="status"></div>
        <div class="small text-muted mt-2">Loading notifications…</div>
    </div>

    <div id="notificationsAllEmpty" class="text-center py-4 d-none">
        <i class="fa-regular fa-bell-slash fs-4 text-muted"></i>
        <div class="mt-2 small text-muted">You have no notifications.</div>
    </div>

    <div id="notificationsAllList" class="d-flex flex-column gap-2"></div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn-gwb-secondary" id="notificationsLoadMoreBtn" style="display:none;">
            <i class="fa-solid fa-rotate-right me-1"></i> Load more
        </button>
    </div>
</div>
@endsection

