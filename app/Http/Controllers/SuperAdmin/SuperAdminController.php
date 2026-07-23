<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SuperAdminController extends Controller
{
    /**
     * Display Super Admin platform overview dashboard.
     */
    public function index()
    {
        // 1. SaaS Platform Summary Metrics
        $stats = [
            'total_gyms' => [
                'value' => 48,
                'growth' => '+6 this month',
                'trend' => 'positive',
                'label' => 'Active Gym Centers'
            ],
            'total_users' => [
                'value' => '3,820',
                'growth' => '+240 members',
                'trend' => 'positive',
                'label' => 'Total Users & Members'
            ],
            'saas_revenue' => [
                'value' => '₹4,85,000',
                'growth' => '+22.4% MoM',
                'trend' => 'positive',
                'label' => 'SaaS Subscription Revenue'
            ],
            'contact_requests' => [
                'value' => 14,
                'growth' => '5 unread leads',
                'trend' => 'warning',
                'label' => 'New Contact Inquiries'
            ]
        ];

        // 2. Chart labels for monthly subscription sales
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
        $chartData = [12, 19, 25, 32, 38, 42, 48];

        // 3. Recent Gym Registrations
        $recentGyms = [
            [
                'name' => 'Iron Pulse Fitness',
                'owner' => 'Vikram Malhotra',
                'email' => 'owner@ironpulse.com',
                'city' => 'Mumbai',
                'plan' => 'Enterprise Pro',
                'status' => 'Active',
                'registered' => Carbon::now()->subDays(2)->format('M d, Y')
            ],
            [
                'name' => 'PowerHouse Gym Center',
                'owner' => 'Sunil Verma',
                'email' => 'sunil@powerhouse.com',
                'city' => 'Delhi',
                'plan' => 'Growth Tier',
                'status' => 'Active',
                'registered' => Carbon::now()->subDays(5)->format('M d, Y')
            ],
            [
                'name' => 'FitStudio CrossFit',
                'owner' => 'Neha Sharma',
                'email' => 'neha@fitstudio.com',
                'city' => 'Bengaluru',
                'plan' => 'Starter Pack',
                'status' => 'Pending Approval',
                'registered' => Carbon::now()->subDays(7)->format('M d, Y')
            ]
        ];

        // 4. Recent Contact Inquiries
        $recentContacts = [
            [
                'name' => 'Rajesh Khanna',
                'email' => 'rajesh@gymchain.in',
                'phone' => '+91 98111 22233',
                'gym' => 'Olympus Gyms (3 Branches)',
                'message' => 'Interested in multi-branch enterprise setup pricing.',
                'date' => Carbon::now()->subHours(4)->format('M d, H:i')
            ],
            [
                'name' => 'Aniket Roy',
                'email' => 'aniket@fitlife.com',
                'phone' => '+91 99887 11223',
                'gym' => 'FitLife Studio',
                'message' => 'Want a demo for custom mobile app branding.',
                'date' => Carbon::now()->subDays(1)->format('M d, H:i')
            ]
        ];

        return view('super-admin.dashboard', compact('stats', 'chartLabels', 'chartData', 'recentGyms', 'recentContacts'));
    }
}
