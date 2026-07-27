@extends('layouts.dashboard')

@section('title', 'Create Group Class')
@section('page_heading', 'Create Group Class')

@section('content')
<div class="mb-4">
    <a href="{{ route('gym-owner.classes.index') }}" class="text-decoration-none text-muted small">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Classes
    </a>
</div>

<div class="gwb-card">
    <h2 class="fw-bold text-white mb-1 fs-4">New Group Class</h2>
    <p class="text-muted small mb-4 border-bottom border-secondary pb-3">Fill in the class details, schedule, and trainer assignment.</p>

    <form action="{{ route('gym-owner.classes.store') }}" method="POST" id="classForm">
        @csrf

        {{-- Basic Details --}}
        <h3 class="fw-semibold text-white fs-6 text-uppercase mb-3" style="letter-spacing:.06em;color:#ea580c!important;">
            <i class="fa-solid fa-circle-info me-2 text-orange"></i>Basic Details
        </h3>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label text-white small fw-semibold">Class Name *</label>
                <input type="text" id="name" name="name"
                       class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="e.g. High-Intensity HIIT" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="category" class="form-label text-white small fw-semibold">Category *</label>
                <select id="category" name="category"
                        class="form-select bg-dark border-secondary text-white @error('category') is-invalid @enderror" required>
                    <option value="" disabled selected>Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-6 col-md-2">
                <label for="duration_minutes" class="form-label text-white small fw-semibold">Duration (mins) *</label>
                <input type="number" id="duration_minutes" name="duration_minutes" min="5" max="480"
                       class="form-control bg-dark border-secondary text-white @error('duration_minutes') is-invalid @enderror"
                       value="{{ old('duration_minutes', 60) }}" required>
                @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-6 col-md-1">
                <label for="capacity" class="form-label text-white small fw-semibold">Capacity *</label>
                <input type="number" id="capacity" name="capacity" min="1" max="500"
                       class="form-control bg-dark border-secondary text-white @error('capacity') is-invalid @enderror"
                       value="{{ old('capacity', 20) }}" required>
                @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="description" class="form-label text-white small fw-semibold">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="form-control bg-dark border-secondary text-white @error('description') is-invalid @enderror"
                          placeholder="Describe what members can expect from this class...">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Schedule --}}
        <h3 class="fw-semibold text-white fs-6 text-uppercase mb-3" style="letter-spacing:.06em;color:#ea580c!important;">
            <i class="fa-solid fa-calendar-days me-2 text-orange"></i>Schedule
        </h3>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <label class="form-label text-white small fw-semibold">Recurring Days</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($daysOfWeek as $day)
                        <div class="day-toggle {{ in_array($day, old('schedule_days', [])) ? 'selected' : '' }}"
                             data-day="{{ $day }}" onclick="toggleDay(this)">
                            {{ $day }}
                            <input type="checkbox" name="schedule_days[]" value="{{ $day }}" class="d-none day-checkbox"
                                   {{ in_array($day, old('schedule_days', [])) ? 'checked' : '' }}>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-6 col-md-3">
                <label for="start_time" class="form-label text-white small fw-semibold">Start Time</label>
                <input type="time" id="start_time" name="start_time"
                       class="form-control bg-dark border-secondary text-white @error('start_time') is-invalid @enderror"
                       value="{{ old('start_time') }}">
                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-6 col-md-3">
                <label for="start_date" class="form-label text-white small fw-semibold">Start Date</label>
                <input type="date" id="start_date" name="start_date"
                       class="form-control bg-dark border-secondary text-white @error('start_date') is-invalid @enderror"
                       value="{{ old('start_date') }}">
                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-6 col-md-3">
                <label for="end_date" class="form-label text-white small fw-semibold">End Date</label>
                <input type="date" id="end_date" name="end_date"
                       class="form-control bg-dark border-secondary text-white @error('end_date') is-invalid @enderror"
                       value="{{ old('end_date') }}">
                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-6 col-md-3">
                <label for="location" class="form-label text-white small fw-semibold">Location / Room</label>
                <input type="text" id="location" name="location"
                       class="form-control bg-dark border-secondary text-white @error('location') is-invalid @enderror"
                       value="{{ old('location') }}" placeholder="e.g. Studio A">
                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Trainer & Status --}}
        <h3 class="fw-semibold text-white fs-6 text-uppercase mb-3" style="letter-spacing:.06em;color:#ea580c!important;">
            <i class="fa-solid fa-user-ninja me-2 text-orange"></i>Trainer &amp; Status
        </h3>
        <div class="row g-3 mb-5">
            <div class="col-12 col-md-6">
                <label for="trainer_id" class="form-label text-white small fw-semibold">Assign Trainer</label>
                <select id="trainer_id" name="trainer_id"
                        class="form-select bg-dark border-secondary text-white @error('trainer_id') is-invalid @enderror">
                    <option value="">— No trainer assigned —</option>
                    @foreach($trainers as $trainer)
                        <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                            {{ $trainer->full_name }} — {{ $trainer->specialization ?? 'General' }}
                        </option>
                    @endforeach
                </select>
                @error('trainer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="status" class="form-label text-white small fw-semibold">Status *</label>
                <select id="status" name="status"
                        class="form-select bg-dark border-secondary text-white @error('status') is-invalid @enderror" required>
                    <option value="active"   {{ old('status', 'active') == 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="border-top border-secondary pt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('gym-owner.classes.index') }}" class="btn btn-gwb-secondary">Cancel</a>
            <button type="submit" class="btn btn-gwb-primary px-4">Create Class</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .day-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 38px;
        border-radius: 8px;
        border: 1px solid var(--gwb-border, #374151);
        color: #9ca3af;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        background: var(--gwb-surface-2, #1a1a1a);
        user-select: none;
    }
    .day-toggle:hover { border-color: #ea580c; color: #ea580c; }
    .day-toggle.selected {
        background: rgba(234,88,12,.15);
        border-color: #ea580c;
        color: #ea580c;
    }
</style>
@endpush

@push('scripts')
<script>
function toggleDay(el) {
    el.classList.toggle('selected');
    const cb = el.querySelector('.day-checkbox');
    cb.checked = el.classList.contains('selected');
}
</script>
@endpush
