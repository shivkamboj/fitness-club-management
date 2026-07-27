<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DietPlanMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'diet_plan_id',
        'meal_number',
        'meal_name',
        'time_of_day',
        'food_items',
        'calories',
        'protein_g',
        'carbs_g',
        'fat_g',
        'notes',
        'order',
    ];

    public function dietPlan(): BelongsTo
    {
        return $this->belongsTo(DietPlan::class);
    }
}
