<?php

namespace App\Services;

use App\Models\GroupClass;
use App\Models\GroupClassBooking;
use App\Models\GymSetting;
use App\Models\Lead;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Carbon;

class GymOwnerDashboardService
{
    /**
     * Build all dashboard data for a gym owner.
     *
     * @return array{
     *     stats: array<string, array{value: int|string, growth: string, trend: string, label: string}>,
     *     chartLabels: list<string>,
     *     chartData: list<int>,
     *     recentMembers: list<array{id: int, name: string, email: string, plan: string, join_date: string, status: string, avatar: string}>,
     *     expiringMembers: list<array{name: string, plan: string, end_date: string, days_left: int}>,
     *     leadStats: array{total: int, new: int, follow_up: int, converted: int},
     *     gymName: string
     * }
     */
    public function getDashboardData(int $gymOwnerId): array
    {
        $membersQuery = fn () => User::where('gym_owner_id', $gymOwnerId)->whereIn('role', [User::ROLE_MEMBER, 5]);

        $activeMembers = (clone $membersQuery())->where('status', User::STATUS_ACTIVE)->count();
        $thisMonthMembers = (clone $membersQuery())
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        $lastMonthMembers = (clone $membersQuery())
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        $memberGrowth = $this->growthPercent($thisMonthMembers, $lastMonthMembers);

        $trainersCount = Trainer::ownedBy($gymOwnerId)->count();
        $activeTrainers = Trainer::ownedBy($gymOwnerId)->active()->count();

        [$todayClasses, $todayBookings, $trainersOnShift] = $this->getTodayClassStats($gymOwnerId);

        [$revenueValue, $revenueGrowth, $revenueTrend] = $this->getRevenueStats($gymOwnerId);

        $stats = [
            'total_members' => [
                'value' => $activeMembers,
                'growth' => $memberGrowth['text'],
                'trend' => $memberGrowth['trend'],
                'label' => 'Active Gym Members',
            ],
            'trainers_count' => [
                'value' => $trainersCount,
                'growth' => $trainersOnShift > 0
                    ? "{$trainersOnShift} scheduled today"
                    : "{$activeTrainers} active",
                'trend' => 'positive',
                'label' => 'Personal Trainers',
            ],
            'revenue_this_month' => [
                'value' => $revenueValue,
                'growth' => $revenueGrowth,
                'trend' => $revenueTrend,
                'label' => 'Est. Monthly Revenue',
            ],
            'today_classes' => [
                'value' => $todayClasses,
                'growth' => $todayBookings > 0 ? "{$todayBookings} booked" : 'No bookings yet',
                'trend' => $todayClasses > 0 ? 'positive' : 'warning',
                'label' => "Today's Group Classes",
            ],
        ];

        [$chartLabels, $chartData] = $this->getSignupChart($gymOwnerId);
        $recentMembers = $this->getRecentMembers($gymOwnerId);
        $expiringMembers = $this->getExpiringMembers($gymOwnerId);
        $leadStats = $this->getLeadStats($gymOwnerId);

        $gymName = GymSetting::getValue($gymOwnerId, 'gym_name')
            ?? User::find($gymOwnerId)?->gym_name
            ?? 'My Fitness Gym';

        return compact('stats', 'chartLabels', 'chartData', 'recentMembers', 'expiringMembers', 'leadStats', 'gymName');
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private function getTodayClassStats(int $gymOwnerId): array
    {
        $today = Carbon::today();
        $todayDay = $today->format('D');

        $classes = GroupClass::where('gym_owner_id', $gymOwnerId)
            ->where('status', 'active')
            ->whereJsonContains('schedule_days', $todayDay)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        $classIds = $classes->pluck('id');

        $bookings = $classIds->isEmpty()
            ? 0
            : GroupClassBooking::whereIn('group_class_id', $classIds)
                ->where('status', 'booked')
                ->count();

        $trainersOnShift = $classes->pluck('trainer_id')->filter()->unique()->count();

        return [$classes->count(), $bookings, $trainersOnShift];
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    private function getRevenueStats(int $gymOwnerId): array
    {
        $activeMembers = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->where('status', User::STATUS_ACTIVE)
            ->whereNotNull('membership_plan_id')
            ->with('membershipPlan')
            ->get();

        $totalRevenue = $activeMembers->sum(fn (User $m) => (float) ($m->membershipPlan?->price ?? 0));

        $thisMonthNew = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->where('created_at', '>=', now()->startOfMonth())
            ->whereNotNull('membership_plan_id')
            ->with('membershipPlan')
            ->get()
            ->sum(fn (User $m) => (float) ($m->membershipPlan?->price ?? 0));

        $lastMonthNew = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->whereNotNull('membership_plan_id')
            ->with('membershipPlan')
            ->get()
            ->sum(fn (User $m) => (float) ($m->membershipPlan?->price ?? 0));

        $growth = $this->growthPercent((int) round($thisMonthNew), (int) round($lastMonthNew));

        return [
            $this->formatCurrency($totalRevenue, $gymOwnerId),
            $growth['text'].' new sales',
            $growth['trend'],
        ];
    }

    /**
     * @return array{0: list<string>, 1: list<int>}
     */
    private function getSignupChart(int $gymOwnerId): array
    {
        $start = Carbon::now()->subDays(29)->startOfDay();

        $counts = User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as signup_date, COUNT(*) as total')
            ->groupBy('signup_date')
            ->pluck('total', 'signup_date');

        $chartLabels = [];
        $chartData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $chartData[] = (int) ($counts[$date->format('Y-m-d')] ?? 0);
        }

        return [$chartLabels, $chartData];
    }

    /**
     * @return list<array{id: int, name: string, email: string, plan: string, join_date: string, status: string, avatar: string}>
     */
    private function getRecentMembers(int $gymOwnerId): array
    {
        return User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->with('membershipPlan')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (User $member) => [
                'id' => $member->id,
                'name' => $member->full_name,
                'email' => $member->email ?? '—',
                'plan' => $member->membershipPlan?->name ?? 'No plan',
                'join_date' => $member->created_at?->format('M d, Y') ?? '—',
                'status' => ucfirst($member->status ?? User::STATUS_ACTIVE),
                'avatar' => $this->initials($member->full_name),
            ])
            ->all();
    }

    /**
     * @return list<array{name: string, plan: string, end_date: string, days_left: int}>
     */
    private function getExpiringMembers(int $gymOwnerId): array
    {
        return User::where('gym_owner_id', $gymOwnerId)
            ->whereIn('role', [User::ROLE_MEMBER, 5])
            ->whereNotNull('membership_expires_at')
            ->where('membership_expires_at', '>=', now()->startOfDay())
            ->where('membership_expires_at', '<=', now()->addDays(14)->endOfDay())
            ->with('membershipPlan')
            ->orderBy('membership_expires_at')
            ->limit(10)
            ->get()
            ->map(fn (User $member) => [
                'name' => $member->full_name,
                'plan' => $member->membershipPlan?->name ?? 'Membership',
                'end_date' => $member->membership_expires_at?->format('M d, Y') ?? '—',
                'days_left' => max(0, (int) now()->startOfDay()->diffInDays($member->membership_expires_at, false)),
            ])
            ->all();
    }

    /**
     * @return array{total: int, new: int, follow_up: int, converted: int}
     */
    private function getLeadStats(int $gymOwnerId): array
    {
        $leads = Lead::where('gym_owner_id', $gymOwnerId)->get();

        return [
            'total' => $leads->count(),
            'new' => $leads->where('status', Lead::STATUS_NEW)->count(),
            'follow_up' => $leads->where('status', Lead::STATUS_FOLLOW_UP)->count(),
            'converted' => $leads->where('status', Lead::STATUS_CONVERTED)->count(),
        ];
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

    private function formatCurrency(float $amount, int $gymOwnerId): string
    {
        $symbol = GymSetting::getValue($gymOwnerId, 'currency_symbol', '₹') ?? '₹';
        $position = GymSetting::getValue($gymOwnerId, 'currency_position', 'before') ?? 'before';
        $formatted = number_format($amount, 0, '.', ',');

        return $position === 'after' ? "{$formatted}{$symbol}" : "{$symbol}{$formatted}";
    }

    private function initials(string $name): string
    {
        $parts = array_filter(explode(' ', trim($name)));
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $last = mb_substr($parts[1] ?? '', 0, 1);

        return mb_strtoupper($first.$last) ?: '?';
    }
}
