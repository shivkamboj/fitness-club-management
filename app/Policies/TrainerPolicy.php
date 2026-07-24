<?php

namespace App\Policies;

use App\Models\Trainer;
use App\Models\User;

class TrainerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isGymOwner();
    }

    public function view(User $user, Trainer $trainer): bool
    {
        return $this->ownsTrainer($user, $trainer);
    }

    public function create(User $user): bool
    {
        return $user->isGymOwner();
    }

    public function update(User $user, Trainer $trainer): bool
    {
        return $this->ownsTrainer($user, $trainer);
    }

    public function delete(User $user, Trainer $trainer): bool
    {
        return $this->ownsTrainer($user, $trainer);
    }

    public function updateStatus(User $user, Trainer $trainer): bool
    {
        return $this->ownsTrainer($user, $trainer);
    }

    private function ownsTrainer(User $user, Trainer $trainer): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return (int) $user->role === User::ROLE_GYM_OWNER
            && (int) $trainer->gym_owner_id === (int) $user->id
            && (int) $trainer->role === User::ROLE_TRAINER;
    }
}
