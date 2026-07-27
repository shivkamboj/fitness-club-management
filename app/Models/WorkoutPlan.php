<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_owner_id',
        'name',
        'description',
        'goal',
        'days_per_week',
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

    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutPlanExercise::class)->orderBy('day_number')->orderBy('order');
    }

    public function assignedMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workout_plan_assignments', 'workout_plan_id', 'user_id')
            ->withPivot('assigned_by', 'assigned_at', 'status')
            ->withTimestamps();
    }
}
