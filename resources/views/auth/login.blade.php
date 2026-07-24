<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Gym Website Builder</title>
    <meta name="description" content="Log in to your Gym Website Builder dashboard to manage your gym website, membership, and marketing plans.">
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('gwb_theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>

    <div class="auth-page">

        {{-- ============================= LEFT BRAND PANEL ============================= --}}
        <div class="auth-side">
            <div class="auth-side-content">
                <a class="gwb-brand d-inline-block" href="{{ url('/') }}">
                    GYM<span>WEBSITE</span>BUILDER
                </a>
            </div>

            <div class="auth-side-content auth-side-quote">
                <div class="stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p>"Our new website brought in more membership inquiries in the first month than we had in the previous six."</p>
                <div class="who">— Rohit Sharma, Owner, PowerHouse Gym</div>
            </div>

            <div class="auth-side-content auth-side-stats">
                <div>
                    <span class="num">250+</span>
                    <span class="label">Gyms Launched</span>
                </div>
                <div>
                    <span class="num">4 Days</span>
                    <span class="label">Avg. Delivery</span>
                </div>
                <div>
                    <span class="num">98%</span>
                    <span class="label">Retention</span>
                </div>
            </div>
        </div>

        {{-- ============================= RIGHT FORM PANEL ============================= --}}
        <div class="auth-form-panel">
            <div class="auth-box">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <a href="{{ url('/') }}" class="auth-back-link mb-0">
                        <i class="fa-solid fa-arrow-left"></i> Back to Home
                    </a>
                    <button type="button" class="theme-toggle-btn" aria-label="Toggle Light/Dark Theme">
                        <i class="fa-solid fa-sun"></i>
                        <span class="theme-toggle-label d-none d-sm-inline">Mode</span>
                    </button>
                </div>

                <h1>Welcome Back</h1>
                <p class="auth-sub">Log in to manage your gym website, membership plans, and marketing dashboard.</p>

                {{-- ── Login Form ─────────────────────────────────────────── --}}
                <form id="loginForm" method="POST" action="{{ route('login.submit') }}" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label-gwb">Email Address</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-envelope leading-icon"></i>
                            <input type="email" id="email" name="email"
                                   class="form-control form-control-gwb"
                                   placeholder="you@example.com" autocomplete="email" autofocus>
                        </div>
                        {{-- Inline field error --}}
                        <span class="invalid-feedback-gwb d-none" id="err-email"></span>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label-gwb">Password</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-lock leading-icon"></i>
                            <input type="password" id="password" name="password"
                                   class="form-control form-control-gwb"
                                   placeholder="Enter your password" autocomplete="current-password">
                            <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        {{-- Inline field error --}}
                        <span class="invalid-feedback-gwb d-none" id="err-password"></span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <label class="form-check-gwb">
                            <input type="checkbox" name="remember" id="remember">
                            Remember me
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="auth-forgot">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" id="loginBtn" class="btn btn-gwb-primary w-100">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Log In
                    </button>
                </form>

                <div class="auth-divider">or continue with</div>

                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn-social">
                            <i class="fa-brands fa-google"></i> Google
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="btn-social">
                            <i class="fa-brands fa-facebook-f"></i> Facebook
                        </a>
                    </div>
                </div>

                <p class="auth-footer-note">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one now</a>
                </p>
            </div>
        </div>
    </div>

    {{-- Toastr: CSS + JS + session flash auto-fire --}}
    @include('partials.toastr')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Shared UI utilities: password toggle (no AJAX logic here) --}}
    <script src="{{ asset('js/auth.js') }}"></script>

    {{-- ═══════════════════════════════════════════════════════════════════════
         LOGIN PAGE — Inline AJAX + Inline Validation
         Scoped entirely to this page. No global dependencies except showToast.
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <script>
    (function () {
        'use strict';

        /* ── Field registry: maps server field name → error span id ───────── */
        var FIELDS = {
            email:    'err-email',
            password: 'err-password',
        };

        /* ── Helpers ──────────────────────────────────────────────────────── */

        /** Show an inline error under a specific field */
        function showFieldError(field, message) {
            var spanId = FIELDS[field];
            if (!spanId) return;

            var input = document.querySelector('[name="' + field + '"]');
            var span  = document.getElementById(spanId);

            if (input) input.classList.add('is-invalid-gwb');
            if (span)  { span.textContent = message; span.classList.remove('d-none'); }
        }

        /** Clear all inline field errors on the form */
        function clearFieldErrors() {
            Object.keys(FIELDS).forEach(function (field) {
                var input = document.querySelector('[name="' + field + '"]');
                var span  = document.getElementById(FIELDS[field]);

                if (input) input.classList.remove('is-invalid-gwb');
                if (span)  { span.textContent = ''; span.classList.add('d-none'); }
            });
        }

        /** Toggle submit button loading state */
        function setLoading(btn, loading) {
            btn.disabled = loading;
            btn.innerHTML = loading
                ? '<i class="fa-solid fa-spinner fa-spin me-2"></i> Logging in…'
                : '<i class="fa-solid fa-right-to-bracket me-2"></i> Log In';
        }

        /* ── Client-side pre-validation ───────────────────────────────────── */
        function validateBeforeSubmit() {
            clearFieldErrors();
            var valid = true;

            var email    = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value;

            if (!email) {
                showFieldError('email', 'Email address is required.');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showFieldError('email', 'Please enter a valid email address.');
                valid = false;
            }

            if (!password) {
                showFieldError('password', 'Password is required.');
                valid = false;
            }

            return valid;
        }

        /* ── AJAX Submit ──────────────────────────────────────────────────── */
        var form = document.getElementById('loginForm');
        var btn  = document.getElementById('loginBtn');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            /* 1. Client-side check first */
            if (!validateBeforeSubmit()) return;

            /* 2. Lock button */
            setLoading(btn, true);

            try {
                var response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':           'application/json',
                    },
                    body: new FormData(form),
                });

                var data = await response.json();

                if (data.success) {
                    /* ✅ Success — toast then redirect */
                    showToast(data.message || 'Login successful!', 'success');
                    setTimeout(function () {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1200);

                } else {
                    /* Unverified email — send user to OTP page */
                    if (data.redirect && response.status === 403) {
                        showToast(data.message || 'Please verify your email.', 'error');
                        setTimeout(function () {
                            window.location.href = data.redirect;
                        }, 1200);
                        return;
                    }

                    /* ❌ Server validation errors — inline per field */
                    if (data.errors && Object.keys(data.errors).length) {
                        Object.entries(data.errors).forEach(function (entry) {
                            var field   = entry[0];
                            var messages = entry[1];
                            showFieldError(field, Array.isArray(messages) ? messages[0] : messages);
                        });
                    } else {
                        /* General error (wrong credentials, etc.) */
                        showToast(data.message || 'Something went wrong.', 'error');
                    }

                    setLoading(btn, false);
                }

            } catch (err) {
                showToast('Network error. Please check your connection.', 'error');
                setLoading(btn, false);
            }
        });

        /* ── Clear field error on user input ──────────────────────────────── */
        Object.keys(FIELDS).forEach(function (field) {
            var input = document.querySelector('[name="' + field + '"]');
            if (!input) return;
            input.addEventListener('input', function () {
                input.classList.remove('is-invalid-gwb');
                var span = document.getElementById(FIELDS[field]);
                if (span) { span.textContent = ''; span.classList.add('d-none'); }
            });
        });

    })();
    </script>
    <script src="{{ asset('js/theme-toggle.js') }}"></script>

</body>
</html>
