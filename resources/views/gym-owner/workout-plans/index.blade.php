@extends('layouts.dashboard')

@section('title', 'Workout Plans')
@section('page_heading', 'Workout Plans Management')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">Workout Plans & Routines</h2>
        <p class="text-muted mb-0 small">Create customized exercise routines (sets, reps, rest periods) and assign to members.</p>
    </div>
    <div>
        <a href="{{ route('gym-owner.workout-plans.create') }}" class="btn btn-gwb-primary py-2 px-3 text-decoration-none">
            <i class="fa-solid fa-plus me-1"></i> Create Workout Plan
        </a>
    </div>
</div>

<div class="row g-4">
    @forelse($plans as $plan)
        <!-- Workout Plan Card -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="gwb-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-dark text-orange border border-secondary">{{ $plan->goal }}</span>
                        <span class="small text-muted">{{ $plan->days_per_week }} {{ Str::plural('Day', $plan->days_per_week) }} / Week</span>
                    </div>
                    <h3 class="fw-bold text-white fs-4 mb-2">{{ $plan->name }}</h3>
                    <p class="text-muted small mb-3">{{ $plan->description ?: 'No description provided.' }}</p>
                    
                    <div class="p-3 bg-dark rounded border border-secondary mb-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Exercises:</span>
                            <span class="text-white fw-semibold">{{ $plan->exercises_count }} {{ Str::plural('Exercise', $plan->exercises_count) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Assigned Members:</span>
                            <span class="text-orange fw-bold">{{ $plan->assigned_members_count }} {{ Str::plural('Member', $plan->assigned_members_count) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Created By:</span>
                            <span class="text-white">{{ $plan->creator ? $plan->creator->full_name : 'System' }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-auto">
                    <a href="{{ route('gym-owner.workout-plans.edit', $plan->id) }}" class="btn btn-gwb-secondary w-50 py-2 text-center text-decoration-none small">
                        <i class="fa-solid fa-pen me-1"></i> Edit
                    </a>
                    
                    <button class="btn btn-gwb-primary w-50 py-2 small assign-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#assignPlanModal" 
                            data-plan-id="{{ $plan->id }}" 
                            data-plan-name="{{ $plan->name }}" 
                            data-assigned-members="{{ json_encode($plan->assignedMembers->pluck('id')->toArray()) }}">
                        <i class="fa-solid fa-user-plus me-1"></i> Assign
                    </button>

                    <form action="{{ route('gym-owner.workout-plans.destroy', $plan->id) }}" method="POST" class="d-inline delete-plan-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger py-2 px-3 small delete-plan-btn" title="Delete Plan">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <!-- Empty State -->
        <div class="col-12 text-center py-5">
            <div class="gwb-card p-5 max-w-md mx-auto">
                <i class="fa-solid fa-dumbbell text-muted fs-1 mb-3"></i>
                <h3 class="fw-bold text-white fs-4 mb-2">No Workout Plans Yet</h3>
                <p class="text-muted mb-4">Start by creating your first custom workout plan to assign to your gym members.</p>
                <a href="{{ route('gym-owner.workout-plans.create') }}" class="btn btn-gwb-primary py-2 px-4 text-decoration-none">
                    <i class="fa-solid fa-plus me-1"></i> Create Workout Plan
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Assign Plan Modal -->
<div class="modal fade" id="assignPlanModal" tabindex="-1" aria-labelledby="assignPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <form id="assignPlanForm" method="POST" action="">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-white" id="assignPlanModalLabel">Assign Workout Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Select members to assign this workout plan. Unchecking a member will deactivate their assignment.</p>
                    <h6 class="fw-semibold text-orange mb-3" id="modalPlanName">Plan Name</h6>
                    <div style="max-height: 300px; overflow-y: auto;" class="pe-2 gwb-member-assign-list">
                        @forelse($members as $member)
                            <label class="gwb-member-assign-item" for="member-{{ $member->id }}">
                                <input class="gwb-assign-checkbox member-checkbox" type="checkbox" name="members[]" value="{{ $member->id }}" id="member-{{ $member->id }}">
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
        const assignPlanModal = document.getElementById('assignPlanModal');
        if (assignPlanModal) {
            assignPlanModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const planId = button.getAttribute('data-plan-id');
                const planName = button.getAttribute('data-plan-name');
                const assignedMembers = JSON.parse(button.getAttribute('data-assigned-members') || '[]');

                // Set Form Action
                const form = document.getElementById('assignPlanForm');
                form.action = `/gym-owner/workout-plans/${planId}/assign`;

                // Set Plan Name
                document.getElementById('modalPlanName').textContent = planName;

                // Reset and Check checkboxes
                const checkboxes = document.querySelectorAll('.member-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = assignedMembers.includes(parseInt(cb.value));
                    cb.closest('.gwb-member-assign-item')?.classList.toggle('is-selected', cb.checked);
                });
            });
        }

        // Sync selected row styling when toggling checkboxes
        document.querySelectorAll('.member-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                this.closest('.gwb-member-assign-item')?.classList.toggle('is-selected', this.checked);
            });
        });

        // Delete plan with SweetAlert2 confirmation
        document.querySelectorAll('.delete-plan-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                const form = this.closest('.delete-plan-form');
                const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
                
                Swal.fire({
                    title: 'Delete Workout Plan?',
                    text: "Are you sure? This action cannot be undone and will remove all exercises and active assignments.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c', // orange color matching theme
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
