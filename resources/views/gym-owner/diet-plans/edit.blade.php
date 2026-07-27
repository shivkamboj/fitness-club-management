@extends('layouts.dashboard')

@section('title', 'Edit Diet Plan')
@section('page_heading', 'Edit Diet Plan')

@section('content')
<div class="mb-4">
    <a href="{{ route('gym-owner.diet-plans.index') }}" class="text-decoration-none text-muted small">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Diet Plans
    </a>
</div>

<div class="gwb-card">
    <h2 class="fw-bold text-white mb-4 fs-4 border-bottom border-secondary pb-3">Edit Diet Plan</h2>

    <form action="{{ route('gym-owner.diet-plans.update', $dietPlan->id) }}" method="POST" id="editDietPlanForm">
        @csrf
        @method('PUT')

        {{-- Plan Info --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label text-white small fw-semibold">Plan Name *</label>
                <input type="text"
                       class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name', $dietPlan->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-3">
                <label for="goal" class="form-label text-white small fw-semibold">Goal / Type *</label>
                <select class="form-select bg-dark border-secondary text-white @error('goal') is-invalid @enderror"
                        id="goal" name="goal" required>
                    <option value="" disabled>Select Goal</option>
                    @foreach(['Muscle Gain','Caloric Deficit','Maintenance','Weight Loss','Lean Bulk','Performance','Vegan','Keto'] as $g)
                        <option value="{{ $g }}" {{ old('goal', $dietPlan->goal) == $g ? 'selected' : '' }}>{{ $g }}</option>
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
                        <option value="{{ $i }}" {{ old('meals_per_day', $dietPlan->meals_per_day) == $i ? 'selected' : '' }}>
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
                          id="description" name="description" rows="3">{{ old('description', $dietPlan->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Macro Targets --}}
        <h3 class="fw-bold text-white mb-3 fs-5 border-bottom border-secondary pb-2">Daily Macro Targets</h3>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <label for="total_calories" class="form-label text-white small fw-semibold">Total Calories (kcal)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('total_calories') is-invalid @enderror"
                       id="total_calories" name="total_calories" value="{{ old('total_calories', $dietPlan->total_calories) }}">
                @error('total_calories')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3">
                <label for="protein_g" class="form-label text-white small fw-semibold">Protein (g)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('protein_g') is-invalid @enderror"
                       id="protein_g" name="protein_g" value="{{ old('protein_g', $dietPlan->protein_g) }}">
                @error('protein_g')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3">
                <label for="carbs_g" class="form-label text-white small fw-semibold">Carbohydrates (g)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('carbs_g') is-invalid @enderror"
                       id="carbs_g" name="carbs_g" value="{{ old('carbs_g', $dietPlan->carbs_g) }}">
                @error('carbs_g')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6 col-md-3">
                <label for="fat_g" class="form-label text-white small fw-semibold">Fat (g)</label>
                <input type="number" min="0"
                       class="form-control bg-dark border-secondary text-white @error('fat_g') is-invalid @enderror"
                       id="fat_g" name="fat_g" value="{{ old('fat_g', $dietPlan->fat_g) }}">
                @error('fat_g')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Meal Breakdown --}}
        <h3 class="fw-bold text-white mb-3 fs-5 border-bottom border-secondary pb-2">Meal Breakdown</h3>
        <p class="text-muted small mb-4">Edit the meals for this diet plan. Changing the number of meals will add/remove meal cards below.</p>

        <div id="mealsContainer" class="d-flex flex-column gap-4 mb-4">
            {{-- Rendered by JS with existing data --}}
        </div>

        <div class="border-top border-secondary pt-3 d-flex justify-content-end gap-2">
            <a href="{{ route('gym-owner.diet-plans.index') }}" class="btn btn-gwb-secondary">Cancel</a>
            <button type="submit" class="btn btn-gwb-primary px-4">Update Diet Plan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mealsSelect = document.getElementById('meals_per_day');
        const container   = document.getElementById('mealsContainer');

        const mealLabels = [
            'Breakfast', 'Morning Snack', 'Lunch', 'Afternoon Snack',
            'Pre-Workout', 'Post-Workout', 'Dinner', 'Evening Snack',
            'Late-Night Snack', 'Supplement Shake'
        ];

        // Existing meal data from the server
        const existingMeals = @json($dietPlan->meals->values());

        function getMealCardHtml(index, data = {}) {
            const defaultLabel = mealLabels[index] || `Meal ${index + 1}`;
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
                                       value="${escHtml(data.meal_name || defaultLabel)}"
                                       placeholder="e.g. ${defaultLabel}" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label text-white small fw-semibold">Time of Day</label>
                                <input type="text"
                                       class="form-control form-control-sm bg-dark border-secondary text-white"
                                       name="meals[${index}][time_of_day]"
                                       value="${escHtml(data.time_of_day || '')}"
                                       placeholder="e.g. 7:00 AM">
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-white small fw-semibold">Cal</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][calories]"
                                       value="${data.calories || ''}" placeholder="kcal">
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-white small fw-semibold">P (g)</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][protein_g]"
                                       value="${data.protein_g || ''}" placeholder="g">
                            </div>
                            <div class="col-6 col-md-1">
                                <label class="form-label text-white small fw-semibold">C (g)</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][carbs_g]"
                                       value="${data.carbs_g || ''}" placeholder="g">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label text-white small fw-semibold">F (g)</label>
                                <input type="number" min="0"
                                       class="form-control form-control-sm bg-dark border-secondary text-white text-center"
                                       name="meals[${index}][fat_g]"
                                       value="${data.fat_g || ''}" placeholder="g">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white small fw-semibold">Food Items *</label>
                                <textarea class="form-control form-control-sm bg-dark border-secondary text-white"
                                          name="meals[${index}][food_items]"
                                          rows="2"
                                          placeholder="e.g. Oats 100g, Banana 1 medium, Whey Protein 30g" required>${escHtml(data.food_items || '')}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-white small fw-semibold">Notes</label>
                                <input type="text"
                                       class="form-control form-control-sm bg-dark border-secondary text-white"
                                       name="meals[${index}][notes]"
                                       value="${escHtml(data.notes || '')}"
                                       placeholder="e.g. Take 30 min before workout">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function captureCurrentValues() {
            const values = {};
            container.querySelectorAll('.meal-card').forEach(card => {
                const i = card.dataset.index;
                values[i] = {
                    meal_name:   card.querySelector(`[name="meals[${i}][meal_name]"]`)?.value   || '',
                    time_of_day: card.querySelector(`[name="meals[${i}][time_of_day]"]`)?.value || '',
                    food_items:  card.querySelector(`[name="meals[${i}][food_items]"]`)?.value  || '',
                    calories:    card.querySelector(`[name="meals[${i}][calories]"]`)?.value    || '',
                    protein_g:   card.querySelector(`[name="meals[${i}][protein_g]"]`)?.value   || '',
                    carbs_g:     card.querySelector(`[name="meals[${i}][carbs_g]"]`)?.value     || '',
                    fat_g:       card.querySelector(`[name="meals[${i}][fat_g]"]`)?.value       || '',
                    notes:       card.querySelector(`[name="meals[${i}][notes]"]`)?.value       || '',
                };
            });
            return values;
        }

        function renderMeals(count, sourceData) {
            const current = captureCurrentValues();
            container.innerHTML = '';

            for (let i = 0; i < count; i++) {
                // Priority: current form values → sourceData (existing meals) → empty
                const data = current[i] && current[i].meal_name
                    ? current[i]
                    : (sourceData[i] || {});
                container.insertAdjacentHTML('beforeend', getMealCardHtml(i, data));
            }
        }

        mealsSelect.addEventListener('change', function () {
            renderMeals(parseInt(this.value), existingMeals);
        });

        // Initial render with existing data
        renderMeals(parseInt(mealsSelect.value), existingMeals);
    });
</script>
@endpush
