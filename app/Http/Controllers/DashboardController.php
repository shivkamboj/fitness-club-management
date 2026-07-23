<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main Admin Dashboard overview page.
     */
    public function index()
    {
        // 1. Dashboard Metrics Summary
        $stats = [
            'total_members' => [
                'value' => 248,
                'growth' => '+14.2%',
                'trend' => 'positive',
                'label' => 'Total Registered Members'
            ],
            'active_subscriptions' => [
                'value' => 195,
                'growth' => '78.6% active',
                'trend' => 'positive',
                'label' => 'Active Subscriptions'
            ],
            'revenue_this_month' => [
                'value' => '₹1,48,500',
                'growth' => '+18.5%',
                'trend' => 'positive',
                'label' => 'Revenue (Current Month)'
            ],
            'expiring_this_week' => [
                'value' => 12,
                'growth' => 'Action required',
                'trend' => 'warning',
                'label' => 'Expiring Within 7 Days'
            ]
        ];

        // 2. Build 30-day member sign-up timeline data for Chart.js
        $chartLabels = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('M d');
            // Mock realistic sign-up distribution curve
            $chartData[] = rand(3, 15);
        }

        // 3. Recent Member Registrations
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
            ],
            [
                'id' => 3,
                'name' => 'Rohan Verma',
                'email' => 'rohan.v@example.com',
                'plan' => 'Quarterly Beast',
                'join_date' => Carbon::now()->subDays(3)->format('M d, Y'),
                'status' => 'Active',
                'avatar' => 'RV'
            ],
            [
                'id' => 4,
                'name' => 'Sneha Gupta',
                'email' => 'sneha.g@example.com',
                'plan' => 'Basic Monthly',
                'join_date' => Carbon::now()->subDays(4)->format('M d, Y'),
                'status' => 'Expiring Soon',
                'avatar' => 'SG'
            ],
            [
                'id' => 5,
                'name' => 'Vikram Singh',
                'email' => 'vikram.s@example.com',
                'plan' => 'Annual Pro Flex',
                'join_date' => Carbon::now()->subDays(5)->format('M d, Y'),
                'status' => 'Active',
                'avatar' => 'VS'
            ],
        ];

        // 4. Members with Subscriptions Expiring Soon
        $expiringMembers = [
            [
                'name' => 'Sneha Gupta',
                'plan' => 'Basic Monthly',
                'end_date' => Carbon::now()->addDays(2)->format('M d, Y'),
                'days_left' => 2
            ],
            [
                'name' => 'Kabir Malhotra',
                'plan' => 'Quarterly Beast',
                'end_date' => Carbon::now()->addDays(3)->format('M d, Y'),
                'days_left' => 3
            ],
            [
                'name' => 'Ananya Roy',
                'plan' => 'Monthly Elite',
                'end_date' => Carbon::now()->addDays(5)->format('M d, Y'),
                'days_left' => 5
            ]
        ];

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartData', 'recentMembers', 'expiringMembers'));
    }
}
