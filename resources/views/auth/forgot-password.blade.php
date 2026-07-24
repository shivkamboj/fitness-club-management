<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password | Gym Website Builder</title>
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
        <div class="auth-side">
            <div class="auth-side-content">
                <a class="gwb-brand d-inline-block" href="{{ url('/') }}">
                    GYM<span>WEBSITE</span>BUILDER
                </a>
            </div>
            <div class="auth-side-content auth-side-quote">
                <div class="stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p>"We'll email you a one-time code so you can securely reset your password."</p>
                <div class="who">— Gym Website Builder</div>
            </div>
        </div>

        <div class="auth-form-panel">
            <div class="auth-box">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <a href="{{ route('login') }}" class="auth-back-link mb-0">
                        <i class="fa-solid fa-arrow-left"></i> Back to Login
                    </a>
                    <button type="button" class="theme-toggle-btn" aria-label="Toggle Light/Dark Theme">
                        <i class="fa-solid fa-sun"></i>
                        <span class="theme-toggle-label d-none d-sm-inline">Mode</span>
                    </button>
                </div>

                <h1>Forgot Password</h1>
                <p class="auth-sub">Enter your registered email and we'll send a 6-digit OTP to reset your password.</p>

                <form id="forgotForm" method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label-gwb">Email Address</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-envelope leading-icon"></i>
                            <input type="email" id="email" name="email"
                                   class="form-control form-control-gwb"
                                   placeholder="you@example.com" autocomplete="email" autofocus>
                        </div>
                        <span class="invalid-feedback-gwb d-none" id="err-email"></span>
                    </div>

                    <button type="submit" id="forgotBtn" class="btn btn-gwb-primary w-100">
                        <i class="fa-solid fa-paper-plane me-2"></i> Send OTP
                    </button>
                </form>

                <p class="auth-footer-note">
                    Remember your password?
                    <a href="{{ route('login') }}">Log in</a>
                </p>
            </div>
        </div>
    </div>

    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
    <script>
    (function () {
        'use strict';

        var form = document.getElementById('forgotForm');
        var btn = document.getElementById('forgotBtn');
        var emailInput = document.getElementById('email');
        var errEmail = document.getElementById('err-email');

        function showError(message) {
            emailInput.classList.add('is-invalid-gwb');
            errEmail.textContent = message;
            errEmail.classList.remove('d-none');
        }

        function clearError() {
            emailInput.classList.remove('is-invalid-gwb');
            errEmail.textContent = '';
            errEmail.classList.add('d-none');
        }

        function setLoading(loading) {
            btn.disabled = loading;
            btn.innerHTML = loading
                ? '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending…'
                : '<i class="fa-solid fa-paper-plane me-2"></i> Send OTP';
        }

        emailInput.addEventListener('input', clearError);

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearError();

            var email = emailInput.value.trim();
            if (!email) {
                showError('Email address is required.');
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('Please enter a valid email address.');
                return;
            }

            setLoading(true);

            try {
                var response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                });

                var data = await response.json();

                if (data.success) {
                    showToast(data.message || 'OTP sent!', 'success');
                    setTimeout(function () {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    if (data.errors && data.errors.email) {
                        showError(Array.isArray(data.errors.email) ? data.errors.email[0] : data.errors.email);
                    } else {
                        showToast(data.message || 'Unable to send OTP.', 'error');
                    }
                    setLoading(false);
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
                setLoading(false);
            }
        });
    })();
    </script>
</body>
</html>
