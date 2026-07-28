<?php

namespace App\Services;

use App\Models\GymSetting;
use App\Models\MembershipPlan;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Carbon;

class SuperAdminDashboardService
{
    /**
     * @return array{
     *     stats: array<string, array{value: int|string, growth: string, trend: string, label: string}>,
     *     chartLabels: list<string>,
     *     chartData: list<int>,
     *     recentGyms: list<array{name: string, owner: string, email: string, city: string, plan: string, status: string, registered: string, members: int}>,
     *     recentContacts: list<array{name: string, email: string, phone: string, gym: string, message: string, date: string, is_unread: bool}>
     * }
     */
    public function getDashboardData(int $superAdminId): array
    {
        $gymOwnersQuery = fn () => User::where('role', User::ROLE_GYM_OWNER);

        $activeGyms = (clone $gymOwnersQuery())->where('status', User::STATUS_ACTIVE)->count();
        $gymsThisMonth = (clone $gymOwnersQuery())
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $gymsLastMonth = (clone $gymOwnersQuery())
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        $gymGrowth = $this->growthPercent($gymsThisMonth, $gymsLastMonth);

        $totalUsers = User::count();
        $usersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
        $usersLastMonth = User::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth(),
        ])->count();

        $totalMembers = User::whereIn('role', [User::ROLE_MEMBER, 5])->count();
        $membersThisMonth = User::whereIn('role', [User::ROLE_MEMBER, 5])
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        [$platformRevenue, $revenueGrowth, $revenueTrend] = $this->getPlatformRevenueStats();

        $contactTotal = UserNotification::where('user_id', $superAdminId)
            ->where('reference_type', 'contact')
            ->count();
        $unreadContacts = UserNotification::where('user_id', $superAdminId)
            ->where('reference_type', 'contact')
            ->whereNull('read_at')
            ->count();

        $stats = [
            'total_gyms' => [
                'value' => $activeGyms,
                'growth' => $gymsThisMonth > 0 ? "+{$gymsThisMonth} this month" : $gymGrowth['text'],
                'trend' => $gymsThisMonth > 0 || $gymGrowth['trend'] === 'positive' ? 'positive' : $gymGrowth['trend'],
                'label' => 'Active Gym Centers',
            ],
            'total_users' => [
                'value' => number_format($totalUsers),
                'growth' => $usersThisMonth > 0 ? "+{$usersThisMonth} this month" : '+0 this month',
                'trend' => $usersThisMonth > 0 ? 'positive' : 'warning',
                'label' => 'Total Platform Users',
            ],
            'saas_revenue' => [
                'value' => $platformRevenue,
                'growth' => $revenueGrowth,
                'trend' => $revenueTrend,
                'label' => 'Est. Membership Volume',
            ],
            'contact_requests' => [
                'value' => $contactTotal,
                'growth' => $unreadContacts > 0 ? "{$unreadContacts} unread" : 'All reviewed',
                'trend' => $unreadContacts > 0 ? 'warning' : 'positive',
                'label' => 'Contact Inquiries',
            ],
        ];

        [$chartLabels, $chartData] = $this->getGymRegistrationChart();
        $recentGyms = $this->getRecentGyms();
        $recentContacts = $this->getRecentContacts($superAdminId);

        return compact('stats', 'chartLabels', 'chartData', 'recentGyms', 'recentContacts', 'totalMembers', 'membersThisMonth');
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    private function getPlatformRevenueStats(): array
    {
        $activeMembers = User::whereIn('role', [User::ROLE_MEMBER, 5])
            ->where('status', User::STATUS_ACTIVE)
            ->whereNotNull('membership_plan_id')
            ->with('membershipPlan')
            ->get();

        $totalRevenue = $activeMembers->sum(fn (User $m) => (float) ($m->membershipPlan?->price ?? 0));

        $thisMonthNew = User::whereIn('role', [User::ROLE_MEMBER, 5])
            ->where('created_at', '>=', now()->startOfMonth())
            ->whereNotNull('membership_plan_id')
            ->with('membershipPlan')
            ->get()
            ->sum(fn (User $m) => (float) ($m->membershipPlan?->price ?? 0));

        $lastMonthNew = User::whereIn('role', [User::ROLE_MEMBER, 5])
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->whereNotNull('membership_plan_id')
            ->with('membershipPlan')
            ->get()
            ->sum(fn (User $m) => (float) ($m->membershipPlan?->price ?? 0));

        $growth = $this->growthPercent((int) round($thisMonthNew), (int) round($lastMonthNew));

        return [
            $this->formatCurrency($totalRevenue),
            $growth['text'].' new sales',
            $growth['trend'],
        ];
    }

    /**
     * @return array{0: list<string>, 1: list<int>}
     */
    private function getGymRegistrationChart(): array
    {
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->format('M Y');

            $chartData[] = User::where('role', User::ROLE_GYM_OWNER)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [$chartLabels, $chartData];
    }

    /**
     * @return list<array{name: string, owner: string, email: string, city: string, plan: string, status: string, registered: string, members: int}>
     */
    private function getRecentGyms(): array
    {
        return User::where('role', User::ROLE_GYM_OWNER)
            ->withCount(['members' => fn ($q) => $q->whereIn('role', [User::ROLE_MEMBER, 5])])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (User $owner) {
                $city = GymSetting::getValue($owner->id, 'city')
                    ?? GymSetting::getValue($owner->id, 'address')
                    ?? '—';

                $planCount = MembershipPlan::ownedBy($owner->id)->active()->count();
                $planLabel = $planCount > 0 ? "{$planCount} ".str('plan')->plural($planCount) : 'No plans yet';

                return [
                    'name' => $owner->gym_name ?: ($owner->full_name.' Gym'),
                    'owner' => $owner->full_name,
                    'email' => $owner->email ?? '—',
                    'city' => $city,
                    'plan' => $planLabel,
                    'status' => ucfirst($owner->status ?? User::STATUS_ACTIVE),
                    'registered' => $owner->created_at?->format('M d, Y') ?? '—',
                    'members' => (int) $owner->members_count,
                ];
            })
            ->all();
    }

    /**
     * @return list<array{name: string, email: string, phone: string, gym: string, message: string, date: string, is_unread: bool}>
     */
    private function getRecentContacts(int $superAdminId): array
    {
        return UserNotification::where('user_id', $superAdminId)
            ->where('reference_type', 'contact')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (UserNotification $notification) {
                $parsed = $this->parseContactNotification($notification->message ?? '');

                return [
                    'name' => $parsed['name'],
                    'email' => $parsed['email'],
                    'phone' => $parsed['phone'],
                    'gym' => $parsed['gym'],
                    'message' => $notification->message ?? '—',
                    'date' => $notification->created_at?->format('M d, H:i') ?? '—',
                    'is_unread' => $notification->read_at === null,
                ];
            })
            ->all();
    }

    /**
     * @return array{name: string, email: string, phone: string, gym: string}
     */
    private function parseContactNotification(string $message): array
    {
        $name = 'Unknown';
        $email = '—';
        $phone = '—';
        $gym = '—';

        if (preg_match('/from\s+(.+?)\s+\(([^)]+)\)/i', $message, $matches)) {
            $name = trim($matches[1]);
            $email = trim($matches[2]);
        }

        return compact('name', 'email', 'phone', 'gym');
    }

    /**
     * @return array{text: string, trend: string}
     */
    private function growthPercent(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'text' => $current > 0 ? '+100%' : '0%',
                'trend' => $current > 0 ? 'positive' : 'warning',
            ];
        }

        $pct = (($current - $previous) / $previous) * 100;
        $sign = $pct >= 0 ? '+' : '';

        return [
            'text' => $sign.number_format($pct, 1).'%',
            'trend' => $pct >= 0 ? 'positive' : 'danger',
        ];
    }

    private function formatCurrency(float $amount): string
    {
        return '₹'.number_format($amount, 0, '.', ',');
    }
}
