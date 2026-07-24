<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $trainer = $request->user();

        return view('trainer.dashboard', [
            'trainer' => $trainer,
        ]);
    }
}
