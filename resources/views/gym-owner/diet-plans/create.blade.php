@extends('layouts.dashboard')

@section('title', 'Create Diet Plan')
@section('page_heading', 'Create Diet Plan')

@section('content')
<div class="mb-4">
    <a href="{{ route('gym-owner.diet-plans.index') }}" class="text-decoration-none text-muted small">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Diet Plans
    </a>
</div>

<div class="gwb-card">
    <h2 class="fw-bold text-white mb-4 fs-4 border-bottom border-secondary pb-3">Diet Plan Details</h2>

    <form action="{{ route('gym-owner.diet-plans.store') }}" method="POST" id="dietPlanForm">
        @csrf

        {{-- Plan Info --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label text-white small fw-semibold">Plan Name *</label>
                <input type="text"
                       class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name') }}"
                       placeholder="e.g. High Protein Lean Bulk" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="goal" class="form-label text-white small fw-semibold">Goal / Type *</label>
                <select class="form-select bg-dark border-secondary text-white @error('goal') is-invalid @enderror"
                        id="goal" name="goal" required>
                    <option value="" disabled selected>Select Goal</option>
                    @foreach(['Muscle Gain','Caloric Deficit','Maintenance','Weight Loss','Lean Bulk','Performance','Vegan','Keto'] as $g)
                        <option value="{{ $g }}" {{ old('goal') == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
                @error('goal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="meals_per_day" class="form-label text-white small fw-semibold">Meals / Day *</label>
                <select class="form-select bg-dark border-secondary text-white @error('meals_per_day') is-invalid @enderror"
                        id="meals_per_day" name="meals_per_day" required>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('meals_per_day', 3) == $i ? 'selected' : '' }}>
                            {{ $i }} {{ Str::plural('Meal', $i) }} / Day
                        </option>
                    @endfor
                </select>
                @error('meals_per_day')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="description" class="form-label text-white small fw-semibold">Description</label>
                <textarea class="form-control bg-dark border-secondary text-white @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3"
                          placeholder="Describe the purpose, target audience, and focus of this nutrition plan...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Macro Targets --}}
        <h3 class="fw-bold text-white mb-3 fs-5 border-bottom border-secondary pb-2">Daily Macro Targets</h3>
        <p class="text-muted small mb-3">Set the overall daily nutrition targets for this plan.</p>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <label for="total_calories" class="form-label text-white small fw-semibold">Total Calories (kcal)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('total_calories') is-invalid @enderror"
                       id="total_calories" name="total_calories" value="{{ old('total_calories') }}" placeholder="e.g. 2800">
                @error('total_calories')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3">
                <label for="protein_g" class="form-label text-white small fw-semibold">Protein (g)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('protein_g') is-invalid @enderror"
                       id="protein_g" name="protein_g" value="{{ old('protein_g') }}" placeholder="e.g. 180">
                @error('protein_g')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3">
                <label for="carbs_g" class="form-label text-white small fw-semibold">Carbohydrates (g)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('carbs_g') is-invalid @enderror"
                       id="carbs_g" name="carbs_g" value="{{ old('carbs_g') }}" placeholder="e.g. 320">
                @error('carbs_g')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3">
                <label for="fat_g" class="form-label text-white small fw-semibold">Fat (g)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('fat_g') is-invalid @enderror"
                       id="fat_g" name="fat_g" value="{{ old('fat_g') }}" placeholder="e.g. 65">
                @error('fat_g')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Meal Breakdown --}}
        <h3 class="fw-bold text-white mb-3 fs-5 border-bottom border-secondary pb-2">Meal Breakdown</h3>
        <p class="text-muted small mb-4">Structure the meals for each day. Adjust the number of meals above and fill in the details below.</p>

        <div id="mealsContainer" class="d-flex flex-column gap-4 mb-4">
            {{-- Dynamically rendered via JS --}}
        </div>

        <div class="border-top border-secondary pt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('gym-owner.diet-plans.index') }}" class="btn btn-gwb-secondary">Cancel</a>
            <button type="submit" class="btn btn-gwb-primary px-4">Create Diet Plan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mealsSelect  = document.getElementById('meals_per_day');
        const container    = document.getElementById('mealsContainer');

        const mealLabels = [
            'Breakfast', 'Morning Snack', 'Lunch', 'Afternoon Snack',
            'Pre-Workout', 'Post-Workout', 'Dinner', 'Evening Snack',
            'Late-Night Snack', 'Supplement Shake'
        ];

        function getMealCardHtml(index) {
            const label = mealLabels[index] || `Meal ${index + 1}`;
            return `
                <div class="card bg-dark border-secondary text-white meal-card" id="meal-card-${index}" data-index="${index}">
                    <div class="card-header border-secondary d-flex justify-content-between align-items-center bg-dark py-3">
                        <h5 class="fw-semibold mb-0 text-orange">
                            <i class="fa-solid fa-utensils me-2"></i>Meal ${index + 1}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label text-white small fw-semibold">Meal Name *</label>
                                <input type="text"
                                       class="form-control form-control-sm bg-dark border-secondary text-white"
                                       name="meals[${index}][meal_name]"
                                       placeholder="e.g. ${label}" value="${label}" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label text-white small fw-semibold">Time of Day</label>
                                <input type="text"
                                       class="form-control form-control-sm bg-dark border-secondary text-white"
                                       name="meals[${index}][time_of_day]"
                                       placeholder="e.g. 7:00 AM">
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-white small fw-semibold">Cal</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][calories]" placeholder="kcal">
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-white small fw-semibold">P (g)</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][protein_g]" placeholder="g">
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-white small fw-semibold">C (g)</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][carbs_g]" placeholder="g">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label text-white small fw-semibold">F (g)</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][fat_g]" placeholder="g">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white small fw-semibold">Food Items *</label>
                                <textarea class="form-control form-control-sm bg-dark border-secondary text-white"
                                          name="meals[${index}][food_items]"
                                          rows="2"
                                          placeholder="e.g. Oats 100g, Banana 1 medium, Whey Protein 30g, Almond milk 200ml" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white small fw-semibold">Notes</label>
                                <input type="text"
                                       class="form-control form-control-sm bg-dark border-secondary text-white"
                                       name="meals[${index}][notes]"
                                       placeholder="e.g. Take 30 min before workout">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderMeals(count) {
            // Capture existing values
            const old = {};
            container.querySelectorAll('.meal-card').forEach(card => {
                const i = card.dataset.index;
                old[i] = {
                    meal_name:   card.querySelector(`[name="meals[${i}][meal_name]"]`)?.value || '',
                    time_of_day: card.querySelector(`[name="meals[${i}][time_of_day]"]`)?.value || '',
                    food_items:  card.querySelector(`[name="meals[${i}][food_items]"]`)?.value || '',
                    calories:    card.querySelector(`[name="meals[${i}][calories]"]`)?.value || '',
                    protein_g:   card.querySelector(`[name="meals[${i}][protein_g]"]`)?.value || '',
                    carbs_g:     card.querySelector(`[name="meals[${i}][carbs_g]"]`)?.value || '',
                    fat_g:       card.querySelector(`[name="meals[${i}][fat_g]"]`)?.value || '',
                    notes:       card.querySelector(`[name="meals[${i}][notes]"]`)?.value || '',
                };
            });

            container.innerHTML = '';

            for (let i = 0; i < count; i++) {
                container.insertAdjacentHTML('beforeend', getMealCardHtml(i));

                // Restore saved values
                if (old[i]) {
                    const card = document.getElementById(`meal-card-${i}`);
                    card.querySelector(`[name="meals[${i}][meal_name]"]`).value   = old[i].meal_name;
                    card.querySelector(`[name="meals[${i}][time_of_day]"]`).value = old[i].time_of_day;
                    card.querySelector(`[name="meals[${i}][food_items]"]`).value  = old[i].food_items;
                    card.querySelector(`[name="meals[${i}][calories]"]`).value    = old[i].calories;
                    card.querySelector(`[name="meals[${i}][protein_g]"]`).value   = old[i].protein_g;
                    card.querySelector(`[name="meals[${i}][carbs_g]"]`).value     = old[i].carbs_g;
                    card.querySelector(`[name="meals[${i}][fat_g]"]`).value       = old[i].fat_g;
                    card.querySelector(`[name="meals[${i}][notes]"]`).value       = old[i].notes;
                }
            }
        }

        mealsSelect.addEventListener('change', function () {
            renderMeals(parseInt(this.value));
        });

        // Initialize
        renderMeals(parseInt(mealsSelect.value));
    });
</script>
@endpush
