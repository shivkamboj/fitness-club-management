@extends('layouts.dashboard')

@section('title', 'Edit Workout Plan')
@section('page_heading', 'Edit Workout Plan')

@section('content')
<div class="mb-4">
    <a href="{{ route('gym-owner.workout-plans.index') }}" class="text-decoration-none text-muted small">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Workout Plans
    </a>
</div>

<div class="gwb-card">
    <h2 class="fw-bold text-white mb-4 fs-4 border-bottom border-secondary pb-3">Workout Plan Details</h2>

    <form action="{{ route('gym-owner.workout-plans.update', $workoutPlan->id) }}" method="POST" id="workoutPlanForm">
        @csrf
        @method('PUT')

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label text-white small fw-semibold">Plan Name *</label>
                <input type="text" class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $workoutPlan->name) }}" placeholder="e.g. 4-Day Upper/Lower Split" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="goal" class="form-label text-white small fw-semibold">Goal / Type *</label>
                <select class="form-select bg-dark border-secondary text-white @error('goal') is-invalid @enderror" 
                        id="goal" name="goal" required>
                    <option value="" disabled>Select Goal</option>
                    <option value="Hypertrophy" {{ old('goal', $workoutPlan->goal) == 'Hypertrophy' ? 'selected' : '' }}>Hypertrophy</option>
                    <option value="Fat Loss" {{ old('goal', $workoutPlan->goal) == 'Fat Loss' ? 'selected' : '' }}>Fat Loss</option>
                    <option value="Strength" {{ old('goal', $workoutPlan->goal) == 'Strength' ? 'selected' : '' }}>Strength</option>
                    <option value="Endurance" {{ old('goal', $workoutPlan->goal) == 'Endurance' ? 'selected' : '' }}>Endurance</option>
                    <option value="Cardio" {{ old('goal', $workoutPlan->goal) == 'Cardio' ? 'selected' : '' }}>Cardio</option>
                    <option value="General Fitness" {{ old('goal', $workoutPlan->goal) == 'General Fitness' ? 'selected' : '' }}>General Fitness</option>
                </select>
                @error('goal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="days_per_week" class="form-label text-white small fw-semibold">Days per Week *</label>
                <select class="form-select bg-dark border-secondary text-white @error('days_per_week') is-invalid @enderror" 
                        id="days_per_week" name="days_per_week" required>
                    @for($i = 1; $i <= 7; $i++)
                        <option value="{{ $i }}" {{ old('days_per_week', $workoutPlan->days_per_week) == $i ? 'selected' : '' }}>{{ $i }} {{ Str::plural('Day', $i) }} / Week</option>
                    @endfor
                </select>
                @error('days_per_week')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="description" class="form-label text-white small fw-semibold">Description</label>
                <textarea class="form-control bg-dark border-secondary text-white @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" placeholder="Describe the purpose, target level, and focus of this routine...">{{ old('description', $workoutPlan->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h3 class="fw-bold text-white mb-3 fs-5 border-bottom border-secondary pb-2">Exercise Routines</h3>
        <p class="text-muted small mb-4">Structure your workout day-by-day. Add exercises to each scheduled day below.</p>

        <div id="daysContainer" class="d-flex flex-column gap-4 mb-4">
            <!-- Day sections will be rendered here dynamically via JS and populated with server-side data -->
            @for($d = 1; $d <= $workoutPlan->days_per_week; $d++)
                @php $dayExercises = $exercisesByDay->get($d, collect()); @endphp
                <div class="card bg-dark border-secondary text-white day-card" id="day-card-{{ $d }}" data-day="{{ $d }}">
                    <div class="card-header border-secondary d-flex justify-content-between align-items-center bg-dark py-3">
                        <h5 class="fw-semibold mb-0 text-orange"><i class="fa-solid fa-calendar-day me-2"></i>Day {{ $d }}</h5>
                        <button type="button" class="btn btn-sm btn-gwb-primary add-exercise-btn" data-day="{{ $d }}">
                            <i class="fa-solid fa-plus me-1"></i> Add Exercise
                        </button>
                    </div>
                    <div class="card-body bg-dark">
                        <div class="exercise-rows-container">
                            @foreach($dayExercises as $index => $exercise)
                                <div class="row g-2 align-items-center mb-2 exercise-row" data-index="{{ $index }}">
                                    <div class="col-12 col-md-4">
                                        <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" 
                                               name="exercises[{{ $d }}][{{ $index }}][exercise_name]" value="{{ $exercise->exercise_name }}" placeholder="Exercise Name" required>
                                    </div>
                                    <div class="col-6 col-md-1">
                                        <input type="number" class="form-control form-control-sm bg-dark border-secondary text-white text-center" 
                                               name="exercises[{{ $d }}][{{ $index }}][sets]" value="{{ $exercise->sets }}" placeholder="Sets" min="1" required>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white text-center" 
                                               name="exercises[{{ $d }}][{{ $index }}][reps]" value="{{ $exercise->reps }}" placeholder="Reps" required>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white text-center" 
                                               name="exercises[{{ $d }}][{{ $index }}][rest]" value="{{ $exercise->rest }}" placeholder="Rest">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="url" class="form-control form-control-sm bg-dark border-secondary text-white" 
                                               name="exercises[{{ $d }}][{{ $index }}][video_link]" value="{{ $exercise->video_link }}" placeholder="Video Link">
                                    </div>
                                    <div class="col-12 col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-exercise-btn">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="border-top border-secondary pt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('gym-owner.workout-plans.index') }}" class="btn btn-gwb-secondary">Cancel</a>
            <button type="submit" class="btn btn-gwb-primary px-4">Update Workout Plan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const daysSelect = document.getElementById('days_per_week');
        const container = document.getElementById('daysContainer');
        
        // Counter to keep track of exercise indices per day
        let exerciseIndices = {};

        // Initialize indices based on server-rendered rows
        @for($d = 1; $d <= 7; $d++)
            @php $count = isset($exercisesByDay[$d]) ? count($exercisesByDay[$d]) : 0; @endphp
            exerciseIndices[{{ $d }}] = {{ $count }};
        @endfor

        function getExerciseRowTemplate(dayNumber, index) {
            return `
                <div class="row g-2 align-items-center mb-2 exercise-row" data-index="${index}">
                    <div class="col-12 col-md-4">
                        <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white" 
                               name="exercises[${dayNumber}][${index}][exercise_name]" placeholder="Exercise Name" required>
                    </div>
                    <div class="col-6 col-md-1">
                        <input type="number" class="form-control form-control-sm bg-dark border-secondary text-white text-center" 
                               name="exercises[${dayNumber}][${index}][sets]" placeholder="Sets" min="1" required>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white text-center" 
                               name="exercises[${dayNumber}][${index}][reps]" placeholder="Reps" required>
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="text" class="form-control form-control-sm bg-dark border-secondary text-white text-center" 
                               name="exercises[${dayNumber}][${index}][rest]" placeholder="Rest">
                    </div>
                    <div class="col-6 col-md-2">
                        <input type="url" class="form-control form-control-sm bg-dark border-secondary text-white" 
                               name="exercises[${dayNumber}][${index}][video_link]" placeholder="Video Link">
                    </div>
                    <div class="col-12 col-md-1 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-exercise-btn">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        // Add delete handler to pre-rendered rows
        document.querySelectorAll('.remove-exercise-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.exercise-row').remove();
            });
        });

        // Add add handlers to pre-rendered add-exercise-btns
        document.querySelectorAll('.add-exercise-btn').forEach(btn => {
            const dayNumber = btn.dataset.day;
            btn.addEventListener('click', function() {
                addExerciseRow(dayNumber);
            });
        });

        function addExerciseRow(dayNumber) {
            const card = document.getElementById(`day-card-${dayNumber}`);
            if (!card) return;
            const rowsContainer = card.querySelector('.exercise-rows-container');
            
            if (!exerciseIndices[dayNumber]) {
                exerciseIndices[dayNumber] = 0;
            }
            const index = exerciseIndices[dayNumber]++;
            const rowHtml = getExerciseRowTemplate(dayNumber, index);
            rowsContainer.insertAdjacentHTML('beforeend', rowHtml);
            
            const newRow = rowsContainer.lastElementChild;
            newRow.querySelector('.remove-exercise-btn').addEventListener('click', function() {
                newRow.remove();
            });
        }

        function createDayCard(dayNumber) {
            const dayCard = document.createElement('div');
            dayCard.className = 'card bg-dark border-secondary text-white day-card';
            dayCard.id = `day-card-${dayNumber}`;
            dayCard.dataset.day = dayNumber;
            
            dayCard.innerHTML = `
                <div class="card-header border-secondary d-flex justify-content-between align-items-center bg-dark py-3">
                    <h5 class="fw-semibold mb-0 text-orange"><i class="fa-solid fa-calendar-day me-2"></i>Day ${dayNumber}</h5>
                    <button type="button" class="btn btn-sm btn-gwb-primary add-exercise-btn" data-day="${dayNumber}">
                        <i class="fa-solid fa-plus me-1"></i> Add Exercise
                    </button>
                </div>
                <div class="card-body bg-dark">
                    <div class="exercise-rows-container">
                        <!-- Exercise rows go here -->
                    </div>
                </div>
            `;

            // Setup events
            const addBtn = dayCard.querySelector('.add-exercise-btn');
            addBtn.addEventListener('click', function() {
                addExerciseRow(dayNumber);
            });

            container.appendChild(dayCard);
            addExerciseRow(dayNumber);
        }

        function renderDays(daysCount) {
            // Store current inputs to preserve them if possible
            const oldData = {};
            const dayCards = container.querySelectorAll('.day-card');
            dayCards.forEach(card => {
                const dayNum = card.dataset.day;
                oldData[dayNum] = [];
                const rows = card.querySelectorAll('.exercise-row');
                rows.forEach(row => {
                    const nameInput = row.querySelector(`[name*="[exercise_name]"]`);
                    const setsInput = row.querySelector(`[name*="[sets]"]`);
                    const repsInput = row.querySelector(`[name*="[reps]"]`);
                    const restInput = row.querySelector(`[name*="[rest]"]`);
                    const videoInput = row.querySelector(`[name*="[video_link]"]`);

                    if (nameInput) {
                        oldData[dayNum].push({
                            name: nameInput.value,
                            sets: setsInput.value,
                            reps: repsInput.value,
                            rest: restInput.value,
                            video: videoInput.value
                        });
                    }
                });
            });

            // Clear container
            container.innerHTML = '';
            exerciseIndices = {};

            // Render new count of days
            for (let i = 1; i <= daysCount; i++) {
                // If oldData exists, restore it. Otherwise create day card with default exercise row
                if (oldData[i] && oldData[i].length > 0) {
                    // Create card skeleton
                    const dayCard = document.createElement('div');
                    dayCard.className = 'card bg-dark border-secondary text-white day-card';
                    dayCard.id = `day-card-${i}`;
                    dayCard.dataset.day = i;
                    dayCard.innerHTML = `
                        <div class="card-header border-secondary d-flex justify-content-between align-items-center bg-dark py-3">
                            <h5 class="fw-semibold mb-0 text-orange"><i class="fa-solid fa-calendar-day me-2"></i>Day ${i}</h5>
                            <button type="button" class="btn btn-sm btn-gwb-primary add-exercise-btn" data-day="${i}">
                                <i class="fa-solid fa-plus me-1"></i> Add Exercise
                            </button>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="exercise-rows-container bg-dark"></div>
                        </div>
                    `;
                    container.appendChild(dayCard);
                    
                    const addBtn = dayCard.querySelector('.add-exercise-btn');
                    addBtn.addEventListener('click', function() {
                        addExerciseRow(i);
                    });

                    const rowsContainer = dayCard.querySelector('.exercise-rows-container');
                    exerciseIndices[i] = 0;

                    oldData[i].forEach(data => {
                        const index = exerciseIndices[i]++;
                        const rowHtml = getExerciseRowTemplate(i, index);
                        rowsContainer.insertAdjacentHTML('beforeend', rowHtml);
                        
                        const row = rowsContainer.lastElementChild;
                        row.querySelector(`[name*="[exercise_name]"]`).value = data.name;
                        row.querySelector(`[name*="[sets]"]`).value = data.sets;
                        row.querySelector(`[name*="[reps]"]`).value = data.reps;
                        row.querySelector(`[name*="[rest]"]`).value = data.rest;
                        row.querySelector(`[name*="[video_link]"]`).value = data.video;

                        row.querySelector('.remove-exercise-btn').addEventListener('click', function() {
                            row.remove();
                        });
                    });
                } else {
                    createDayCard(i);
                }
            }
        }

        // Handle change in number of days
        daysSelect.addEventListener('change', function () {
            renderDays(parseInt(this.value));
        });
    });
</script>
@endpush
