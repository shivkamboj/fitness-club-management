<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Access Forbidden | GymForce Platform</title>
    <meta name="description" content="You do not have permission to access this page. Please contact your administrator if you believe this is an error.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script>
        // Apply saved theme instantly — no flash
        (function () {
            const t = localStorage.getItem('gwb_theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    <style>
        /* ── CSS Variables ── */
        :root {
            --bg:        #0a0a0f;
            --surface:   #111118;
            --card:      #18181f;
            --border:    rgba(255,255,255,.08);
            --text:      #f1f5f9;
            --muted:     #64748b;
            --accent:    #dc2626;
            --accent-2:  #ef4444;
            --accent-3:  #f97316;
            --glow:      rgba(220,38,38,.20);
            --shadow:    0 25px 60px rgba(0,0,0,.55);
            --font:      'Inter', sans-serif;
        }
        [data-theme="light"] {
            --bg:      #f1f5f9;
            --surface: #e8edf5;
            --card:    #ffffff;
            --border:  rgba(0,0,0,.08);
            --text:    #0f172a;
            --muted:   #64748b;
            --glow:    rgba(220,38,38,.10);
            --shadow:  0 25px 60px rgba(0,0,0,.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: background .3s, color .3s;
        }

        /* Animated background blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: .3;
            pointer-events: none;
            animation: drift 14s ease-in-out infinite alternate;
        }
        .blob-1 { width: 550px; height: 550px; background: radial-gradient(circle, #dc262655, transparent 70%); top: -180px; right: -150px; animation-delay: 0s; }
        .blob-2 { width: 420px; height: 420px; background: radial-gradient(circle, #9333ea44, transparent 70%); bottom: -120px; left: -100px; animation-delay: -6s; }
        .blob-3 { width: 280px; height: 280px; background: radial-gradient(circle, #f9731633, transparent 70%); top: 40%; left: 15%; animation-delay: -10s; }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(50px, 35px) scale(1.1); }
        }

        /* Dot grid */
        .grid-bg {
            position: fixed; inset: 0; pointer-events: none;
            background-image: radial-gradient(circle, var(--border) 1px, transparent 1px);
            background-size: 36px 36px;
            opacity: .5;
        }

        /* Lock bars animation (decorative) */
        .lock-bars {
            position: fixed; inset: 0; pointer-events: none; overflow: hidden;
        }
        .lock-bar {
            position: absolute;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,.15), transparent);
            animation: scanLine 6s linear infinite;
            left: 0; right: 0;
        }
        .lock-bar:nth-child(1) { top: 25%; animation-delay: 0s; }
        .lock-bar:nth-child(2) { top: 55%; animation-delay: -2s; }
        .lock-bar:nth-child(3) { top: 80%; animation-delay: -4s; }
        @keyframes scanLine {
            0%   { opacity: 0; transform: translateX(-100%); }
            50%  { opacity: 1; }
            100% { opacity: 0; transform: translateX(100%); }
        }

        /* Theme toggle */
        .theme-toggle {
            position: fixed;
            top: 20px; right: 20px;
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 50px;
            padding: 8px 16px;
            cursor: pointer;
            font-family: var(--font);
            font-size: 13px;
            font-weight: 600;
            display: flex; align-items: center; gap: 8px;
            transition: all .25s;
            z-index: 100;
            box-shadow: var(--shadow);
        }
        .theme-toggle:hover { border-color: var(--accent); color: var(--accent); }

        /* Card */
        .error-card {
            position: relative;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: clamp(40px, 6vw, 64px) clamp(32px, 5vw, 80px);
            max-width: 700px;
            width: 100%;
            text-align: center;
            box-shadow: var(--shadow), 0 0 100px var(--glow);
            animation: cardIn .65s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(40px) scale(.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Red border accent top */
        .error-card::before {
            content: '';
            position: absolute;
            top: 0; left: 50%; transform: translateX(-50%);
            width: 200px; height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent), var(--accent-2), transparent);
            border-radius: 0 0 4px 4px;
        }

        /* 403 number */
        .error-number {
            font-size: clamp(80px, 16vw, 140px);
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #dc2626, #ef4444, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -4px;
            animation: flicker 4s ease-in-out infinite;
            margin-bottom: 8px;
        }
        @keyframes flicker {
            0%, 90%, 100% { filter: brightness(1); }
            92%            { filter: brightness(1.4) saturate(1.3); }
            94%            { filter: brightness(.9); }
            96%            { filter: brightness(1.3); }
        }

        /* Divider */
        .error-divider {
            width: 60px; height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--accent-3));
            border-radius: 4px;
            margin: 0 auto 28px;
        }

        /* Shield/lock icon */
        .error-icon-wrap {
            position: relative;
            width: 80px; height: 80px;
            background: linear-gradient(135deg, rgba(220,38,38,.18), rgba(239,68,68,.08));
            border: 1px solid rgba(220,38,38,.35);
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
            font-size: 34px;
            animation: shake 6s ease-in-out infinite;
        }
        .error-icon-wrap::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 26px;
            border: 1px solid rgba(220,38,38,.15);
            animation: ringPulse 3s ease-in-out infinite;
        }
        @keyframes shake {
            0%, 85%, 100%  { transform: translateY(0) rotate(0deg); }
            87%             { transform: translateY(-6px) rotate(-2deg); }
            89%             { transform: translateY(-6px) rotate(2deg); }
            91%             { transform: translateY(0) rotate(0deg); }
        }
        @keyframes ringPulse {
            0%, 100% { opacity: .3; transform: scale(1); }
            50%       { opacity: .7; transform: scale(1.06); }
        }

        .error-title {
            font-size: clamp(24px, 4.5vw, 34px);
            font-weight: 800;
            color: var(--text);
            margin-bottom: 14px;
            line-height: 1.2;
        }

        .error-desc {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.75;
            max-width: 500px;
            margin: 0 auto 32px;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(220,38,38,.1);
            border: 1px solid rgba(220,38,38,.25);
            color: #f87171;
            border-radius: 50px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .status-badge .dot {
            width: 6px; height: 6px;
            background: #ef4444;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: .2; }
        }

        /* Info box */
        .info-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px 20px;
            margin: 0 auto 28px;
            max-width: 480px;
            text-align: left;
        }
        .info-box h4 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 10px;
        }
        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--muted);
            padding: 5px 0;
            border-bottom: 1px solid var(--border);
        }
        .info-row:last-child { border-bottom: none; }
        .info-row i { color: var(--accent-2); width: 16px; text-align: center; }
        .info-row span:last-child { color: var(--text); font-weight: 500; margin-left: auto; }

        /* Actions */
        .error-actions {
            display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;
        }
        .btn-primary-err {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 26px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: var(--font);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .25s;
            box-shadow: 0 4px 20px rgba(220,38,38,.3);
        }
        .btn-primary-err:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(220,38,38,.5); color: #fff; }

        .btn-secondary-err {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 26px;
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: var(--font);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .25s;
        }
        .btn-secondary-err:hover { border-color: var(--accent); color: var(--accent); transform: translateY(-2px); }

        .btn-ghost-err {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 26px;
            background: rgba(220,38,38,.08);
            color: #f87171;
            border: 1px solid rgba(220,38,38,.2);
            border-radius: 12px;
            font-family: var(--font);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .25s;
        }
        .btn-ghost-err:hover { background: rgba(220,38,38,.15); border-color: var(--accent); color: #fca5a5; transform: translateY(-2px); }

        /* Quick links */
        .quick-links {
            margin-top: 36px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }
        .quick-links h4 {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
        }
        .quick-link-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 10px;
            text-decoration: none;
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            display: flex; flex-direction: column; align-items: center; gap: 6px;
            transition: all .2s;
        }
        .quick-link-item i { font-size: 18px; color: var(--accent-2); }
        .quick-link-item:hover { border-color: rgba(220,38,38,.3); color: var(--text); transform: translateY(-3px); box-shadow: 0 8px 20px var(--glow); }

        /* Footer */
        .error-footer {
            margin-top: 40px;
            font-size: 12px;
            color: var(--muted);
            opacity: .6;
            text-align: center;
        }
        .error-footer a { color: var(--accent-3); text-decoration: none; }
        .error-footer a:hover { text-decoration: underline; }

        /* Mobile */
        @media (max-width: 480px) {
            .error-actions { flex-direction: column; }
            .btn-primary-err, .btn-secondary-err, .btn-ghost-err { justify-content: center; }
            .info-row span:last-child { text-align: right; }
        }
    </style>
</head>
<body>

<div class="grid-bg"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>
<div class="lock-bars">
    <div class="lock-bar"></div>
    <div class="lock-bar"></div>
    <div class="lock-bar"></div>
</div>

<!-- Theme Toggle -->
<button class="theme-toggle" onclick="toggleTheme()" id="themeToggleBtn" aria-label="Toggle color theme">
    <i class="fa-solid fa-moon" id="themeIcon"></i>
    <span id="themeLabel">Dark Mode</span>
</button>

<!-- Error Card -->
<div class="error-card" role="main" aria-labelledby="errorTitle">

    <div class="error-number" aria-hidden="true">403</div>
    <div class="error-divider"></div>

    <div class="status-badge" role="status">
        <span class="dot"></span>
        Access Denied
    </div>

    <div class="error-icon-wrap" aria-hidden="true">
        <i class="fa-solid fa-shield-halved" style="color: #ef4444;"></i>
    </div>

    <h1 class="error-title" id="errorTitle">Forbidden — You Shall Not Pass</h1>
    <p class="error-desc">
        You don't have the required permissions to view this page. 
        This area is restricted to authorized users only. If you believe 
        this is a mistake, please contact your gym administrator.
    </p>

    <!-- Request Info -->
    <div class="info-box" aria-label="Request details">
        <h4><i class="fa-solid fa-circle-info me-1"></i> Request Details</h4>
        <div class="info-row">
            <i class="fa-solid fa-globe"></i>
            <span>Requested URL</span>
            <span>{{ url()->current() }}</span>
        </div>
        <div class="info-row">
            <i class="fa-solid fa-clock"></i>
            <span>Timestamp</span>
            <span>{{ now()->format('d M Y, H:i:s') }}</span>
        </div>
        @auth
        <div class="info-row">
            <i class="fa-solid fa-user"></i>
            <span>Logged In As</span>
            <span>{{ Auth::user()->name }}</span>
        </div>
        <div class="info-row">
            <i class="fa-solid fa-id-badge"></i>
            <span>Role</span>
            <span>{{ Auth::user()->role_name ?? 'Member' }}</span>
        </div>
        @else
        <div class="info-row">
            <i class="fa-solid fa-user-slash"></i>
            <span>Logged In As</span>
            <span>Guest (Not Authenticated)</span>
        </div>
        @endauth
    </div>

    <div class="error-actions">
        <a href="{{ url('/dashboard') }}" class="btn-primary-err">
            <i class="fa-solid fa-house"></i> Go to Dashboard
        </a>
        <button onclick="history.back()" class="btn-secondary-err">
            <i class="fa-solid fa-arrow-left"></i> Go Back
        </button>
        @guest
        <a href="{{ route('login') }}" class="btn-ghost-err">
            <i class="fa-solid fa-right-to-bracket"></i> Login
        </a>
        @endguest
    </div>

    <!-- Quick links -->
    <div class="quick-links">
        <h4>Safe Pages You Can Visit</h4>
        <div class="quick-links-grid">
            <a href="{{ url('/dashboard') }}" class="quick-link-item">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>
            @auth
                @if(Auth::user()->isGymOwner() || Auth::user()->isTrainer())
                    <a href="{{ route('gym-owner.members.index') }}" class="quick-link-item">
                        <i class="fa-solid fa-users"></i>
                        <span>Members</span>
                    </a>
                    <a href="{{ route('gym-owner.leads.index') }}" class="quick-link-item">
                        <i class="fa-solid fa-headset"></i>
                        <span>Leads</span>
                    </a>
                    <a href="{{ route('gym-owner.workout-plans.index') }}" class="quick-link-item">
                        <i class="fa-solid fa-dumbbell"></i>
                        <span>Workouts</span>
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="quick-link-item">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Login</span>
                </a>
                <a href="{{ route('home') }}" class="quick-link-item">
                    <i class="fa-solid fa-globe"></i>
                    <span>Homepage</span>
                </a>
                <a href="{{ route('contact.store') }}" class="quick-link-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Contact Us</span>
                </a>
            @endauth
        </div>
    </div>

</div>

<div class="error-footer">
    &copy; {{ date('Y') }} GymForce Platform &mdash;
    <a href="{{ route('home') }}">Homepage</a> &middot;
    <a href="{{ route('contact.store') }}">Contact Support</a>
</div>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const current = html.getAttribute('data-theme') || 'dark';
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('gwb_theme', next);
        updateToggleUI(next);
    }

    function updateToggleUI(theme) {
        const icon  = document.getElementById('themeIcon');
        const label = document.getElementById('themeLabel');
        if (theme === 'dark') {
            icon.className    = 'fa-solid fa-moon';
            label.textContent = 'Dark Mode';
        } else {
            icon.className    = 'fa-solid fa-sun';
            label.textContent = 'Light Mode';
        }
    }

    // Sync on load
    updateToggleUI(document.documentElement.getAttribute('data-theme') || 'dark');
</script>
</body>
</html>
