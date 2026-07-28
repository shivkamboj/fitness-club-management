<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use App\Services\GymOwnerDashboardService;
use Illuminate\Support\Facades\Auth;

class GymOwnerDashboardController extends Controller
{
    public function __construct(
        private readonly GymOwnerDashboardService $dashboardService
    ) {}

    /**
     * Display Gym Owner dashboard overview.
     */
    public function index()
    {
        $gymOwnerId = $this->getGymOwnerId();

        $data = $this->dashboardService->getDashboardData($gymOwnerId);

        return view('gym-owner.dashboard', $data);
    }

    private function getGymOwnerId(): int
    {
        $user = Auth::user();

        return $user->isGymOwner() ? $user->id : ($user->gym_owner_id ?? $user->id);
    }
}
