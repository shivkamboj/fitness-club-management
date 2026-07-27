<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymBranch extends Model
{
    protected $fillable = [
        'gym_owner_id',
        'name',
        'address',
        'phone',
        'email',
        'manager_name',
        'status',
    ];

    public function gymOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gym_owner_id');
    }
}
