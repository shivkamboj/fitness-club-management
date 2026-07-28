<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found | GymForce Platform</title>
    <meta name="description" content="The page you are looking for could not be found. It may have been moved, deleted, or never existed.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script>
        // Apply saved theme instantly to prevent flash
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
            --accent:    #ea580c;
            --accent-2:  #f97316;
            --glow:      rgba(234,88,12,.18);
            --shadow:    0 25px 50px rgba(0,0,0,.5);
            --font:      'Inter', sans-serif;
        }
        [data-theme="light"] {
            --bg:      #f1f5f9;
            --surface: #e8edf5;
            --card:    #ffffff;
            --border:  rgba(0,0,0,.08);
            --text:    #0f172a;
            --muted:   #64748b;
            --glow:    rgba(234,88,12,.10);
            --shadow:  0 25px 50px rgba(0,0,0,.12);
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
            filter: blur(80px);
            opacity: .35;
            pointer-events: none;
            animation: drift 12s ease-in-out infinite alternate;
        }
        .blob-1 { width: 500px; height: 500px; background: radial-gradient(circle, #ea580c55, transparent 70%); top: -150px; left: -150px; animation-delay: 0s; }
        .blob-2 { width: 400px; height: 400px; background: radial-gradient(circle, #7c3aed44, transparent 70%); bottom: -100px; right: -100px; animation-delay: -5s; }
        .blob-3 { width: 300px; height: 300px; background: radial-gradient(circle, #0ea5e933, transparent 70%); top: 50%; left: 60%; animation-delay: -8s; }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(40px, 30px) scale(1.08); }
        }

        /* Grid dots background */
        .grid-bg {
            position: fixed; inset: 0; pointer-events: none;
            background-image: radial-gradient(circle, var(--border) 1px, transparent 1px);
            background-size: 36px 36px;
            opacity: .5;
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
            max-width: 680px;
            width: 100%;
            text-align: center;
            box-shadow: var(--shadow), 0 0 80px var(--glow);
            animation: cardIn .6s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(40px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* 404 number */
        .error-number {
            font-size: clamp(80px, 16vw, 140px);
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, var(--accent), var(--accent-2), #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -4px;
            animation: pulse 3s ease-in-out infinite;
            margin-bottom: 8px;
        }
        @keyframes pulse {
            0%, 100% { filter: brightness(1); }
            50%       { filter: brightness(1.2); }
        }

        /* Divider line */
        .error-divider {
            width: 60px; height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            border-radius: 4px;
            margin: 0 auto 24px;
        }

        .error-icon-wrap {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, rgba(234,88,12,.15), rgba(249,115,22,.08));
            border: 1px solid rgba(234,88,12,.3);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        .error-title {
            font-size: clamp(22px, 4vw, 32px);
            font-weight: 800;
            color: var(--text);
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .error-desc {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 480px;
            margin: 0 auto 32px;
        }

        /* Actions */
        .error-actions {
            display: flex; flex-wrap: wrap; gap: 12px; justify-content: center;
        }
        .btn-primary-err {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 24px;
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
            box-shadow: 0 4px 20px rgba(234,88,12,.3);
        }
        .btn-primary-err:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(234,88,12,.45); color: #fff; }

        .btn-secondary-err {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 24px;
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
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
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
        .quick-link-item i { font-size: 18px; color: var(--accent); }
        .quick-link-item:hover { border-color: var(--accent); color: var(--text); transform: translateY(-3px); box-shadow: 0 8px 20px var(--glow); }

        /* Footer */
        .error-footer {
            margin-top: 40px;
            font-size: 12px;
            color: var(--muted);
            opacity: .6;
        }
        .error-footer a { color: var(--accent); text-decoration: none; }
        .error-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="grid-bg"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<!-- Theme Toggle -->
<button class="theme-toggle" onclick="toggleTheme()" id="themeToggleBtn" aria-label="Toggle theme">
    <i class="fa-solid fa-moon" id="themeIcon"></i>
    <span id="themeLabel">Dark Mode</span>
</button>

<!-- Error Card -->
<div class="error-card" role="main">

    <div class="error-number">404</div>
    <div class="error-divider"></div>

    <div class="error-icon-wrap" aria-hidden="true">
        <i class="fa-solid fa-magnifying-glass" style="color: var(--accent);"></i>
    </div>

    <h1 class="error-title">Page Not Found</h1>
    <p class="error-desc">
        Oops! The page you're looking for has gone off the grid — much like a skipped leg day. 
        It may have been moved, deleted, or it simply never existed.
    </p>

    <div class="error-actions">
        <a href="{{ url('/dashboard') }}" class="btn-primary-err">
            <i class="fa-solid fa-house"></i> Go to Dashboard
        </a>
        <button onclick="history.back()" class="btn-secondary-err">
            <i class="fa-solid fa-arrow-left"></i> Go Back
        </button>
    </div>

    <!-- Quick Navigation -->
    <div class="quick-links">
        <h4>Quick Navigation</h4>
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
                    <a href="{{ route('gym-owner.settings.index') }}" class="quick-link-item">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Settings</span>
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="quick-link-item">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}" class="quick-link-item">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Register</span>
                </a>
                <a href="{{ route('home') }}" class="quick-link-item">
                    <i class="fa-solid fa-globe"></i>
                    <span>Home</span>
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="error-footer">
    &copy; {{ date('Y') }} GymForce Platform &mdash;
    <a href="{{ route('home') }}">Visit Homepage</a>
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
            icon.className  = 'fa-solid fa-moon';
            label.textContent = 'Dark Mode';
        } else {
            icon.className  = 'fa-solid fa-sun';
            label.textContent = 'Light Mode';
        }
    }

    // Sync on load
    updateToggleUI(document.documentElement.getAttribute('data-theme') || 'dark');
</script>
</body>
</html>
