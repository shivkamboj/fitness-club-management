<?php

namespace App\Http\Controllers\GymOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GymOwnerDashboardController extends Controller
{
    /**
     * Display Gym Owner dashboard overview.
     */
    public function index()
    {
        $stats = [
            'total_members' => [
                'value' => 248,
                'growth' => '+14.2%',
                'trend' => 'positive',
                'label' => 'Active Gym Members'
            ],
            'trainers_count' => [
                'value' => 8,
                'growth' => '2 on shift today',
                'trend' => 'positive',
                'label' => 'Personal Trainers'
            ],
            'revenue_this_month' => [
                'value' => '₹1,48,500',
                'growth' => '+18.5%',
                'trend' => 'positive',
                'label' => 'Gym Monthly Revenue'
            ],
            'today_classes' => [
                'value' => 6,
                'growth' => '32 booked',
                'trend' => 'positive',
                'label' => "Today's Group Classes"
            ]
        ];

        $chartLabels = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $chartData[] = rand(3, 15);
        }

        $recentMembers = [
            [
                'id' => 1,
                'name' => 'Aarav Sharma',
                'email' => 'aarav.sharma@example.com',
                'plan' => 'Annual Pro Flex',
                'join_date' => Carbon::now()->subDays(1)->format('M d, Y'),
                'status' => 'Active',
                'avatar' => 'AS'
            ],
            [
                'id' => 2,
                'name' => 'Priya Patel',
                'email' => 'priya.patel@example.com',
                'plan' => 'Monthly Elite',
                'join_date' => Carbon::now()->subDays(2)->format('M d, Y'),
                'status' => 'Active',
                'avatar' => 'PP'
            ]
        ];

        $expiringMembers = [
            [
                'name' => 'Sneha Gupta',
                'plan' => 'Basic Monthly',
                'end_date' => Carbon::now()->addDays(2)->format('M d, Y'),
                'days_left' => 2
            ]
        ];

        return view('gym-owner.dashboard', compact('stats', 'chartLabels', 'chartData', 'recentMembers', 'expiringMembers'));
    }
}
