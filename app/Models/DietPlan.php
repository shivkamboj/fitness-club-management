<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_owner_id',
        'name',
        'description',
        'goal',
        'total_calories',
        'protein_g',
        'carbs_g',
        'fat_g',
        'meals_per_day',
        'created_by',
    ];

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function meals(): HasMany
    {
        return $this->hasMany(DietPlanMeal::class)->orderBy('order')->orderBy('meal_number');
    }

    public function assignedMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'diet_plan_assignments', 'diet_plan_id', 'user_id')
            ->withPivot('assigned_by', 'assigned_at', 'status')
            ->withTimestamps();
    }
}
