<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Gym Management Platform</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Core Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('gwb_theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    @stack('styles')
</head>
<body class="dashboard-body">

    @php
        $authUser = Auth::user();
        $isSuperAdmin = $authUser && method_exists($authUser, 'isSuperAdmin') ? $authUser->isSuperAdmin() : request()->is('super-admin*');
        $isTrainer = $authUser && method_exists($authUser, 'isTrainer') ? $authUser->isTrainer() : request()->is('trainer*');
        $isMember = $authUser && method_exists($authUser, 'isMember') ? $authUser->isMember() : request()->is('member*');
        $dashboardHome = $isSuperAdmin
            ? route('super-admin.dashboard')
            : ($isTrainer ? route('trainer.dashboard') : ($isMember ? route('member.dashboard') : route('gym-owner.dashboard')));
    @endphp

    <div class="app-wrapper" id="appWrapper">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- App Sidebar -->
        <aside class="app-sidebar" id="appSidebar">
            <div class="sidebar-header">
                <a href="{{ $dashboardHome }}" class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="fa-solid fa-dumbbell"></i>
                    </div>
                    <div class="brand-text">
                        GYM<span>FORCE</span>
                    </div>
                </a>
            </div>

            <nav class="sidebar-menu">
                @if($isSuperAdmin)
                    <!-- SUPER ADMIN SIDEBAR (Role = 1, Platform Owner) -->
                    <div class="menu-category">Platform Super Admin</div>

                    <a href="{{ route('super-admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie nav-icon"></i>
                        <span class="nav-text">Platform Overview</span>
                    </a>

                    <a href="{{ route('super-admin.gyms.index') }}" class="nav-link-item {{ request()->routeIs('super-admin.gyms*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building-shield nav-icon"></i>
                        <span class="nav-text">Gym Owners & Gyms</span>
                    </a>

                    <a href="{{ route('super-admin.users.index') }}" class="nav-link-item {{ request()->routeIs('super-admin.users*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear nav-icon"></i>
                        <span class="nav-text">All Platform Users</span>
                    </a>

                    <div class="menu-category">SaaS Subscriptions & Sales</div>

                    <a href="{{ route('super-admin.subscriptions.index') }}" class="nav-link-item {{ request()->routeIs('super-admin.subscriptions*') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt nav-icon"></i>
                        <span class="nav-text">Purchased Subscriptions</span>
                    </a>

                    <a href="{{ route('super-admin.contacts.index') }}" class="nav-link-item {{ request()->routeIs('super-admin.contacts*') ? 'active' : '' }}">
                        <i class="fa-solid fa-headset nav-icon"></i>
                        <span class="nav-text">Contact Requests / Leads</span>
                    </a>
                @elseif($isTrainer)
                    <div class="menu-category">Trainer Panel</div>

                    <a href="{{ route('trainer.dashboard') }}" class="nav-link-item {{ request()->routeIs('trainer.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <a href="{{ route('gym-owner.workout-plans.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.workout-plans*') ? 'active' : '' }}">
                        <i class="fa-solid fa-dumbbell nav-icon"></i>
                        <span class="nav-text">Workout Plans</span>
                    </a>

                    <a href="{{ route('gym-owner.diet-plans.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.diet-plans*') ? 'active' : '' }}">
                        <i class="fa-solid fa-apple-whole nav-icon"></i>
                        <span class="nav-text">Diet Plans</span>
                    </a>

                    <a href="{{ route('gym-owner.leads.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.leads*') ? 'active' : '' }}">
                        <i class="fa-solid fa-headset nav-icon"></i>
                        <span class="nav-text">Leads & Enquiries</span>
                    </a>
                @elseif($isMember)
                    <div class="menu-category">Member Portal</div>

                    <a href="{{ route('member.dashboard') }}" class="nav-link-item {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <a href="{{ route('member.workouts') }}" class="nav-link-item {{ request()->routeIs('member.workouts*') ? 'active' : '' }}">
                        <i class="fa-solid fa-dumbbell nav-icon"></i>
                        <span class="nav-text">My Workouts</span>
                    </a>

                    <a href="{{ route('member.diet-plan') }}" class="nav-link-item {{ request()->routeIs('member.diet-plan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-apple-whole nav-icon"></i>
                        <span class="nav-text">My Diet Plan</span>
                    </a>

                    <a href="{{ route('member.classes') }}" class="nav-link-item {{ request()->routeIs('member.classes*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-days nav-icon"></i>
                        <span class="nav-text">Group Classes</span>
                    </a>
                @else
                    <!-- GYM OWNER SIDEBAR (Role = 2, Gym Subscriber) -->
                    <div class="menu-category">Gym Management</div>

                    <a href="{{ route('gym-owner.dashboard') }}" class="nav-link-item {{ request()->routeIs('gym-owner.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line nav-icon"></i>
                        <span class="nav-text">Gym Dashboard</span>
                    </a>

                    <a href="{{ route('gym-owner.leads.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.leads*') ? 'active' : '' }}">
                        <i class="fa-solid fa-headset nav-icon"></i>
                        <span class="nav-text">Leads & Enquiries</span>
                    </a>

                    <a href="{{ route('gym-owner.members.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.members*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users nav-icon"></i>
                        <span class="nav-text">Members</span>
                    </a>

                    <a href="{{ route('gym-owner.trainers.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.trainers*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-ninja nav-icon"></i>
                        <span class="nav-text">Trainers</span>
                    </a>

                    <a href="{{ route('gym-owner.workout-plans.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.workout-plans*') ? 'active' : '' }}">
                        <i class="fa-solid fa-dumbbell nav-icon"></i>
                        <span class="nav-text">Workout Plans</span>
                    </a>

                    <a href="{{ route('gym-owner.diet-plans.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.diet-plans*') ? 'active' : '' }}">
                        <i class="fa-solid fa-apple-whole nav-icon"></i>
                        <span class="nav-text">Diet Plans</span>
                    </a>

                    <a href="{{ route('gym-owner.classes.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.classes*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-days nav-icon"></i>
                        <span class="nav-text">Classes & Schedules</span>
                    </a>

                    <a href="{{ route('gym-owner.notifications.whatsapp') }}" class="nav-link-item {{ request()->routeIs('gym-owner.notifications.whatsapp*') ? 'active' : '' }}">
                        <i class="fa-brands fa-whatsapp nav-icon" style="color:#25d366;"></i>
                        <span class="nav-text">WhatsApp Events</span>
                    </a>

                    <div class="menu-category">Gym Finance & Billing</div>

                    <a href="{{ route('gym-owner.plans.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.plans*') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags nav-icon"></i>
                        <span class="nav-text">Membership Plans</span>
                    </a>

                    <a href="{{ route('gym-owner.payments.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.payments*') ? 'active' : '' }}">
                        <i class="fa-solid fa-credit-card nav-icon"></i>
                        <span class="nav-text">Payments & Invoices</span>
                    </a>

                    <a href="{{ route('gym-owner.reports.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.reports*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-column nav-icon"></i>
                        <span class="nav-text">Reports</span>
                    </a>

                    <a href="{{ route('gym-owner.settings.index') }}" class="nav-link-item {{ request()->routeIs('gym-owner.settings*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders nav-icon"></i>
                        <span class="nav-text">Gym Settings</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="user-mini-card">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name ?? ($isSuperAdmin ? 'Super Administrator' : ($isTrainer ? 'Trainer' : 'Gym Owner')) }}</div>
                        <div class="user-role">{{ $authUser->role_name ?? ($isSuperAdmin ? 'Super Admin' : ($isTrainer ? 'Trainer' : 'Gym Owner')) }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="app-main">
            <!-- Topbar Header -->
            <header class="app-topbar">
                <div class="topbar-left">
                    <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="topbar-title-section">
                        <h1>@yield('page_heading', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="topbar-badge d-none d-md-flex">
                        <i class="fa-solid {{ $isSuperAdmin ? 'fa-shield-halved text-orange' : ($isTrainer ? 'fa-user-ninja text-orange' : 'fa-building text-orange') }}"></i>
                        <span>
                            @if($isSuperAdmin)
                                Platform Super Admin Console
                            @elseif($isTrainer)
                                Trainer Workspace
                            @else
                                {{ Auth::user()->gym_name ?? 'My Fitness Gym' }}
                            @endif
                        </span>
                    </div>

                    <!-- Theme Switcher Button -->
                    <button type="button" class="theme-toggle-btn" aria-label="Toggle Light/Dark Theme">
                        <i class="fa-solid fa-sun"></i>
                        <span class="theme-toggle-label d-none d-lg-inline">Mode</span>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div class="dropdown notifications-dropdown">
                        <button
                            class="btn btn-gwb-secondary notifications-bell-btn"
                            type="button"
                            id="notificationsBellBtn"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            aria-label="Notifications"
                            title="Notifications"
                        >
                            <i class="fa-regular fa-bell text-orange"></i>
                            <span id="notificationsUnreadBadge" class="notifications-unread-badge d-none" aria-live="polite">0</span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end notifications-dropdown-menu p-2" aria-labelledby="notificationsBellBtn">
                            <div id="notificationsDropdownLoading" class="notifications-dropdown-loading text-center py-3">
                                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                                <div class="small text-muted mt-2">Loading…</div>
                            </div>

                            <div id="notificationsDropdownList" class="d-flex flex-column gap-2"></div>

                            <div id="notificationsDropdownEmpty" class="text-center py-3 d-none">
                                <i class="fa-regular fa-bell-slash fs-4 text-muted"></i>
                                <div class="small text-muted mt-2">No notifications</div>
                            </div>

                            <hr class="border-secondary opacity-25 my-2">

                            <div class="d-grid gap-2 notifications-dropdown-actions">
                                <button type="button" class="btn-gwb-secondary btn-sm" id="notificationsMarkAsReadBtn" disabled>
                                    <i class="fa-solid fa-check me-1"></i> Mark as Read
                                </button>
                                <button type="button" class="btn-gwb-secondary btn-sm" id="notificationsMarkAllReadBtnNavbar">
                                    <i class="fa-solid fa-check-double me-1"></i> Mark All as Read
                                </button>
                                <a href="{{ route('notifications.index') }}" class="btn-gwb-secondary btn-sm text-center text-decoration-none notifications-view-all-btn">
                                    <i class="fa-solid fa-eye me-1"></i> View All Notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-gwb-secondary dropdown-toggle py-2 px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-user me-1 text-orange"></i>
                            <span class="d-none d-sm-inline">{{ Auth::user()->name ?? 'Account' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ $isSuperAdmin ? route('super-admin.dashboard') : ($isTrainer ? route('trainer.dashboard') : route('gym-owner.settings.index')) }}">
                                    <i class="fa-solid fa-gear me-2"></i>
                                    {{ $isSuperAdmin ? 'Platform Settings' : ($isTrainer ? 'My Dashboard' : 'Gym Settings') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                    <i class="fa-solid fa-globe me-2"></i> Public Website
                                </a>
                            </li>
                            <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Main Page Content Body -->
            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-toggle.js') }}"></script>

    @include('partials.toastr')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const appWrapper = document.getElementById('appWrapper');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const mobileOverlay = document.getElementById('mobileOverlay');

            // Responsive Sidebar Toggle
            toggleBtn.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    appWrapper.classList.toggle('mobile-open');
                } else {
                    appWrapper.classList.toggle('collapsed');
                }
            });

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function () {
                    appWrapper.classList.remove('mobile-open');
                });
            }
        });
    </script>

    <script>
        window.gwbNotifications = {
            routes: {
                unreadCount: @json(route('notifications.unread-count')),
                latest: @json(route('notifications.latest')),
                list: @json(route('notifications.list')),
                markRead: @json(route('notifications.mark-read')),
                markAllRead: @json(route('notifications.mark-all-read')),
            }
        };
    </script>

    <script src="{{ asset('js/notifications.js') }}"></script>

    @stack('scripts')
</body>
</html>
