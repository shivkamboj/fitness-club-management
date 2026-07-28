@extends('layouts.dashboard')

@section('title', 'Diet Plans')
@section('page_heading', 'Diet & Nutrition Plans')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Diet &amp; Nutrition Plans</h2>
        <p class="text-muted mb-0 small">Create structured meal plans (timing, food items, calories, macros) and assign to gym members.</p>
    </div>
    <div>
        <a href="{{ route('gym-owner.diet-plans.create') }}" class="btn btn-gwb-primary py-2 px-3 text-decoration-none">
            <i class="fa-solid fa-plus me-1"></i> Create Diet Plan
        </a>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 text-white" style="background: rgba(34,197,94,.15); border-left: 4px solid #22c55e !important;" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    @forelse($plans as $plan)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="gwb-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-dark text-orange border border-secondary">{{ $plan->goal }}</span>
                        <span class="small text-muted fw-semibold">{{ number_format($plan->total_calories) }} kcal</span>
                    </div>
                    <h3 class="fw-bold text-white fs-4 mb-2">{{ $plan->name }}</h3>
                    <p class="text-muted small mb-3">{{ $plan->description ?: 'No description provided.' }}</p>

                    <div class="p-3 bg-dark rounded border border-secondary mb-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Macros:</span>
                            <span class="text-white fw-semibold">
                                {{ $plan->protein_g ?? '—' }}g P
                                | {{ $plan->carbs_g ?? '—' }}g C
                                | {{ $plan->fat_g ?? '—' }}g F
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Meals / Day:</span>
                            <span class="text-white">{{ $plan->meals_per_day }} {{ Str::plural('Meal', $plan->meals_per_day) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Assigned Members:</span>
                            <span class="text-orange fw-bold">{{ $plan->assignedMembers->count() }} {{ Str::plural('Member', $plan->assignedMembers->count()) }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('gym-owner.diet-plans.edit', $plan->id) }}"
                       class="btn btn-gwb-secondary py-2 w-50 text-center text-decoration-none small">
                        <i class="fa-solid fa-pen me-1"></i> Edit Plan
                    </a>

                    <button class="btn btn-gwb-primary py-2 w-50 small assign-diet-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#assignDietModal"
                            data-plan-id="{{ $plan->id }}"
                            data-plan-name="{{ $plan->name }}"
                            data-assigned-members="{{ json_encode($plan->assignedMembers->pluck('id')->toArray()) }}">
                        <i class="fa-solid fa-user-plus me-1"></i> Assign
                    </button>

                    <form action="{{ route('gym-owner.diet-plans.destroy', $plan->id) }}" method="POST" class="d-inline delete-diet-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger py-2 px-3 small delete-diet-btn" title="Delete Plan">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        {{-- Empty State --}}
        <div class="col-12 text-center py-5">
            <div class="gwb-card p-5 mx-auto" style="max-width: 500px;">
                <i class="fa-solid fa-utensils text-muted fs-1 mb-3"></i>
                <h3 class="fw-bold text-white fs-4 mb-2">No Diet Plans Yet</h3>
                <p class="text-muted mb-4">Start by creating your first custom nutrition plan to assign to your gym members.</p>
                <a href="{{ route('gym-owner.diet-plans.create') }}" class="btn btn-gwb-primary py-2 px-4 text-decoration-none">
                    <i class="fa-solid fa-plus me-1"></i> Create Diet Plan
                </a>
            </div>
        </div>
    @endforelse
</div>

{{-- Assign Diet Plan Modal --}}
<div class="modal fade" id="assignDietModal" tabindex="-1" aria-labelledby="assignDietModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <form id="assignDietForm" method="POST" action="">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white" id="assignDietModalLabel">Assign Diet Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Select members to assign this diet plan. Unchecking a member will deactivate their assignment.</p>
                    <h6 class="fw-semibold text-orange mb-3" id="modalDietPlanName">Plan Name</h6>
                    <div style="max-height: 300px; overflow-y: auto;" class="pe-2 gwb-member-assign-list">
                        @forelse($members as $member)
                            <label class="gwb-member-assign-item" for="diet-member-{{ $member->id }}">
                                <input class="gwb-assign-checkbox diet-member-checkbox" type="checkbox"
                                       name="members[]" value="{{ $member->id }}"
                                       id="diet-member-{{ $member->id }}">
                                <span class="gwb-assign-checkbox-ui" aria-hidden="true">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                                <div class="gwb-member-assign-info">
                                    <div class="gwb-member-assign-name">{{ $member->full_name }}</div>
                                    <div class="gwb-member-assign-email">{{ $member->email }}</div>
                                </div>
                            </label>
                        @empty
                            <p class="text-center text-muted py-3">No members registered yet.</p>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-gwb-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gwb-primary">Save Assignments</button>
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

        // Assign modal setup
        const assignDietModal = document.getElementById('assignDietModal');
        if (assignDietModal) {
            assignDietModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const planId = button.getAttribute('data-plan-id');
                const planName = button.getAttribute('data-plan-name');
                const assignedMembers = JSON.parse(button.getAttribute('data-assigned-members') || '[]');

                const form = document.getElementById('assignDietForm');
                form.action = `/gym-owner/diet-plans/${planId}/assign`;

                document.getElementById('modalDietPlanName').textContent = planName;

                const checkboxes = document.querySelectorAll('.diet-member-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = assignedMembers.includes(parseInt(cb.value));
                    cb.closest('.gwb-member-assign-item')?.classList.toggle('is-selected', cb.checked);
                });
            });
        }

        document.querySelectorAll('.diet-member-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                this.closest('.gwb-member-assign-item')?.classList.toggle('is-selected', this.checked);
            });
        });

        // Delete with SweetAlert2 confirmation
        document.querySelectorAll('.delete-diet-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('.delete-diet-form');
                const isDark = document.documentElement.getAttribute('data-theme') !== 'light';

                Swal.fire({
                    title: 'Delete Diet Plan?',
                    text: 'Are you sure? This action cannot be undone and will remove all meals and active member assignments.',
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
