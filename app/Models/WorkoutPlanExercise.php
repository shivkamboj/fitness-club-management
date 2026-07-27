<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutPlanExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_plan_id',
        'day_number',
        'exercise_name',
        'sets',
        'reps',
        'rest',
        'video_link',
        'order',
    ];

    public function workoutPlan(): BelongsTo
    {
        return $this->belongsTo(WorkoutPlan::class);
    }
}
