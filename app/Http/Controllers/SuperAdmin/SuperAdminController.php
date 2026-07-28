<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdminDashboardService;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function __construct(
        private readonly SuperAdminDashboardService $dashboardService
    ) {}

    /**
     * Display Super Admin platform overview dashboard.
     */
    public function index()
    {
        $data = $this->dashboardService->getDashboardData((int) Auth::id());

        return view('super-admin.dashboard', $data);
    }
}
