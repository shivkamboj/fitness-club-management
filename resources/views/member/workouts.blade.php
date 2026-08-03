@extends('layouts.dashboard')

@section('title', 'My Workouts')
@section('page_heading', 'My Workout Routine')

@section('content')
@push('styles')
<style>
    .exercise-card {
        transition: all 0.25s ease;
        background: var(--gwb-surface-2, #1e1e28);
        border: 1px solid var(--gwb-border, #2a2a35);
        border-radius: 12px;
    }
    [data-theme="light"] .exercise-card {
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .exercise-card:hover {
        border-color: var(--gwb-orange, #ff5a1f);
    }
    .exercise-completed {
        border-color: rgba(16, 185, 129, 0.4) !important;
        background: rgba(16, 185, 129, 0.06) !important;
    }
    [data-theme="light"] .exercise-completed {
        background: rgba(16, 185, 129, 0.08) !important;
        border-color: #10b981 !important;
    }
    .exercise-completed .exercise-title {
        text-decoration: line-through;
        opacity: 0.7;
    }
    .custom-checkbox {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        border: 2px solid var(--gwb-border, #4a4a5a);
        background-color: transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    [data-theme="light"] .custom-checkbox {
        border-color: #94a3b8;
    }
    .custom-checkbox:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
</style>
@endpush

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1 fs-3">My Assigned Workout Plan</h2>
        <p class="text-muted mb-0 small">View your weekly split and check off exercises as you complete them daily.</p>
    </div>
</div>

@if($plan)
    <div class="row g-4">
        <!-- Plan Overview Column -->
        <div class="col-12 col-lg-4">
            <div class="gwb-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-dark text-orange border border-secondary">{{ $plan->goal }}</span>
                    <span class="small text-muted fw-semibold"><i class="fa-solid fa-calendar-days text-orange me-1"></i>{{ $plan->days_per_week }} {{ Str::plural('Day', $plan->days_per_week) }} / Week</span>
                </div>
                <h3 class="fw-bold text-white fs-4 mb-2">{{ $plan->name }}</h3>
                <p class="text-muted small mb-4">{{ $plan->description ?: 'Follow this customized training program to reach your physical fitness goals.' }}</p>

                <div class="gwb-widget-box d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Assigned By:</span>
                        <span class="text-white fw-semibold"><i class="fa-solid fa-user-ninja text-warning me-1"></i>{{ $plan->creator ? $plan->creator->full_name : 'Gym Trainer' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total Exercises:</span>
                        <span class="badge bg-dark text-orange border border-secondary px-2.5 py-1 fw-bold fs-6">{{ $plan->exercises->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exercises Routines Column -->
        <div class="col-12 col-lg-8">
            <div class="gwb-card h-100">
                <!-- Days Tab Navigation -->
                <ul class="nav nav-pills gap-2 mb-4" id="workoutDayTabs" role="tablist">
                    @for($d = 1; $d <= $plan->days_per_week; $d++)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($d === 1) active @endif" 
                                    id="day-tab-{{ $d }}" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#day-pane-{{ $d }}" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="day-pane-{{ $d }}" 
                                    aria-selected="{{ $d === 1 ? 'true' : 'false' }}">
                                <i class="fa-solid fa-dumbbell me-1.5 opacity-75"></i>Day {{ $d }}
                            </button>
                        </li>
                    @endfor
                </ul>

                <!-- Days Tab Panes -->
                <div class="tab-content" id="workoutDayTabContent">
                    @for($d = 1; $d <= $plan->days_per_week; $d++)
                        @php $dayExercises = $plan->exercises->where('day_number', $d); @endphp
                        <div class="tab-pane fade @if($d === 1) show active @endif" 
                             id="day-pane-{{ $d }}" 
                             role="tabpanel" 
                             aria-labelledby="day-tab-{{ $d }}" 
                             tabindex="0">
                            
                            @if($dayExercises->count() > 0)
                                <div class="d-flex flex-column gap-3">
                                    @foreach($dayExercises as $exercise)
                                        @php $isCompleted = in_array($exercise->id, $completedExerciseIds); @endphp
                                        <div class="exercise-card p-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 @if($isCompleted) exercise-completed @endif" 
                                             id="exercise-card-{{ $exercise->id }}">
                                            
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="form-check mb-0">
                                                    <input type="checkbox" 
                                                           class="form-check-input custom-checkbox complete-exercise-cb" 
                                                           data-exercise-id="{{ $exercise->id }}"
                                                           @if($isCompleted) checked @endif>
                                                </div>
                                                <div>
                                                    <h4 class="fw-bold text-white fs-5 mb-1 exercise-title">{{ $exercise->exercise_name }}</h4>
                                                    <div class="small text-muted d-flex flex-wrap gap-3">
                                                        <span><i class="fa-solid fa-rotate-left text-orange me-1"></i><strong class="text-white">{{ $exercise->sets }}</strong> sets</span>
                                                        <span><i class="fa-solid fa-dumbbell text-orange me-1"></i><strong class="text-white">{{ $exercise->reps }}</strong> reps</span>
                                                        @if($exercise->rest)
                                                            <span><i class="fa-solid fa-clock text-orange me-1"></i><strong class="text-white">{{ $exercise->rest }}</strong> rest</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if($exercise->video_link)
                                                <a href="{{ $exercise->video_link }}" target="_blank" class="btn btn-sm btn-outline-warning py-1.5 px-3">
                                                    <i class="fa-solid fa-circle-play me-1"></i> Video Guide
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-mug-hot fs-1 mb-2 opacity-50"></i>
                                    <h5 class="text-white fw-bold">Rest Day!</h5>
                                    <p class="mb-0 small">No exercises scheduled for Day {{ $d }}. Rest & recover well.</p>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
@else
    <!-- No Plan Assigned State -->
    <div class="gwb-card text-center py-5 max-w-md mx-auto">
        <i class="fa-solid fa-calendar-xmark text-muted fs-1 mb-3 opacity-50"></i>
        <h3 class="fw-bold text-white fs-4 mb-2">No Active Workout Plan</h3>
        <p class="text-muted mb-0">You don't have an active workout plan assigned. Please check back later or contact your gym trainer to assign one.</p>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle exercise checkbox
        document.querySelectorAll('.complete-exercise-cb').forEach(cb => {
            cb.addEventListener('change', function () {
                const exerciseId = this.getAttribute('data-exercise-id');
                const card = document.getElementById(`exercise-card-${exerciseId}`);
                
                // Disable checkbox temporarily
                this.disabled = true;

                fetch("{{ route('member.workouts.toggle-complete') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        exercise_id: exerciseId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    this.disabled = false;
                    if (data.success) {
                        if (data.completed) {
                            card.classList.add('exercise-completed');
                            toastr.success('Exercise marked as completed!');
                        } else {
                            card.classList.remove('exercise-completed');
                            toastr.info('Exercise marked as incomplete.');
                        }
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                        this.checked = !this.checked;
                    }
                })
                .catch(err => {
                    this.disabled = false;
                    toastr.error('Error contacting server.');
                    this.checked = !this.checked;
                    console.error(err);
                });
            });
        });
    });
</script>
@endpush
