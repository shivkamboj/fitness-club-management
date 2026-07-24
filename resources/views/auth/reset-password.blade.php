<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | Gym Website Builder</title>
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
    <style>
        .otp-expiry-box {
            background: var(--gwb-orange-dim, rgba(255, 90, 31, 0.12));
            border: 1px solid rgba(255, 90, 31, 0.28);
            border-radius: 12px;
            padding: 0.9rem 1rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        .otp-expiry-box.is-expired {
            background: rgba(255, 107, 107, 0.12);
            border-color: rgba(255, 107, 107, 0.4);
        }
        .otp-expiry-label {
            color: var(--gwb-text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .otp-expiry-time {
            font-family: var(--font-mono, 'JetBrains Mono', monospace);
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            color: var(--gwb-orange-light, #ff8a3d);
            line-height: 1.2;
        }
        .otp-expiry-box.is-expired .otp-expiry-time {
            color: #ff6b6b;
            font-size: 1.05rem;
            letter-spacing: 0.02em;
        }
        .otp-expired-msg {
            color: #ff6b6b;
            font-size: 0.92rem;
            margin-top: 0.45rem;
            margin-bottom: 0;
        }
    </style>
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
                <p>"Enter the OTP from your email, then choose a strong new password."</p>
                <div class="who">— Gym Website Builder</div>
            </div>
        </div>

        <div class="auth-form-panel">
            <div class="auth-box">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <a href="{{ route('password.request') }}" class="auth-back-link mb-0">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                    <button type="button" class="theme-toggle-btn" aria-label="Toggle Light/Dark Theme">
                        <i class="fa-solid fa-sun"></i>
                        <span class="theme-toggle-label d-none d-sm-inline">Mode</span>
                    </button>
                </div>

                <h1>Reset Password</h1>
                <p class="auth-sub">
                    We sent an OTP to <strong>{{ $email }}</strong>. Enter it below with your new password.
                </p>

                <div id="otpExpiryBox" class="otp-expiry-box {{ !empty($otpExpired) ? 'is-expired' : '' }}">
                    <div id="otpExpiryLabel" class="otp-expiry-label">
                        {{ !empty($otpExpired) ? 'OTP status' : 'OTP expires in' }}
                    </div>
                    <div id="otpExpiryTime" class="otp-expiry-time">
                        {{ !empty($otpExpired) ? 'Expired' : '--:--' }}
                    </div>
                    <p id="otpExpiredMsg" class="otp-expired-msg {{ empty($otpExpired) ? 'd-none' : '' }}">
                        OTP has expired. Please request a new one to reset your password.
                    </p>
                </div>

                <div id="expiredActions" class="{{ empty($otpExpired) ? 'd-none' : '' }} mb-3">
                    <a href="{{ route('password.request') }}" class="btn btn-gwb-primary w-100">
                        <i class="fa-solid fa-paper-plane me-2"></i> Request New OTP
                    </a>
                </div>

                <form id="resetForm" method="POST" action="{{ route('password.update') }}" novalidate
                      class="{{ !empty($otpExpired) ? 'd-none' : '' }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <label for="otp" class="form-label-gwb">OTP Code</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-shield-halved leading-icon"></i>
                            <input type="text" id="otp" name="otp"
                                   class="form-control form-control-gwb"
                                   placeholder="123456" maxlength="6" inputmode="numeric"
                                   autocomplete="one-time-code" autofocus
                                   {{ !empty($otpExpired) ? 'disabled' : '' }}>
                        </div>
                        <span class="invalid-feedback-gwb d-none" id="err-otp"></span>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label-gwb">New Password</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-lock leading-icon"></i>
                            <input type="password" id="password" name="password"
                                   class="form-control form-control-gwb"
                                   placeholder="Create a new password" autocomplete="new-password"
                                   {{ !empty($otpExpired) ? 'disabled' : '' }}>
                            <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <span class="invalid-feedback-gwb d-none" id="err-password"></span>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label-gwb">Confirm Password</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-lock leading-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control form-control-gwb"
                                   placeholder="Re-enter new password" autocomplete="new-password"
                                   {{ !empty($otpExpired) ? 'disabled' : '' }}>
                            <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Show password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <span class="invalid-feedback-gwb d-none" id="err-password_confirmation"></span>
                    </div>

                    <button type="submit" id="resetBtn" class="btn btn-gwb-primary w-100">
                        <i class="fa-solid fa-key me-2"></i> Change Password
                    </button>
                </form>

                <p class="auth-footer-note">
                    <a href="{{ route('login') }}">Back to login</a>
                </p>
            </div>
        </div>
    </div>

    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
    <script>
    (function () {
        'use strict';

        var FIELDS = {
            otp: 'err-otp',
            password: 'err-password',
            password_confirmation: 'err-password_confirmation',
        };

        var otpExpiresAt = {{ (int) ($otpExpiresAt ?? 0) }} * 1000;
        var initiallyExpired = @json(!empty($otpExpired));
        var forgotUrl = @json(route('password.request'));

        var form = document.getElementById('resetForm');
        var btn = document.getElementById('resetBtn');
        var otpInput = document.getElementById('otp');
        var expiryBox = document.getElementById('otpExpiryBox');
        var expiryLabel = document.getElementById('otpExpiryLabel');
        var expiryTime = document.getElementById('otpExpiryTime');
        var expiredMsg = document.getElementById('otpExpiredMsg');
        var expiredActions = document.getElementById('expiredActions');
        var timerInterval = null;

        function formatTime(ms) {
            var totalSec = Math.max(0, Math.ceil(ms / 1000));
            var mins = Math.floor(totalSec / 60);
            var secs = totalSec % 60;
            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        function markExpired() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }

            expiryBox.classList.add('is-expired');
            expiryLabel.textContent = 'OTP status';
            expiryTime.textContent = 'Expired';
            expiredMsg.classList.remove('d-none');
            expiredActions.classList.remove('d-none');
            form.classList.add('d-none');

            ['otp', 'password', 'password_confirmation'].forEach(function (name) {
                var input = document.querySelector('[name="' + name + '"]');
                if (input) input.disabled = true;
            });
        }

        function tickExpiry() {
            if (!otpExpiresAt) {
                markExpired();
                return;
            }

            var remaining = otpExpiresAt - Date.now();
            if (remaining <= 0) {
                markExpired();
                showToast('OTP has expired. Please request a new one.', 'error');
                return;
            }

            expiryLabel.textContent = 'OTP expires in';
            expiryTime.textContent = formatTime(remaining);
        }

        if (initiallyExpired || !otpExpiresAt || otpExpiresAt <= Date.now()) {
            markExpired();
        } else {
            tickExpiry();
            timerInterval = setInterval(tickExpiry, 1000);
        }

        function showFieldError(field, message) {
            var input = document.querySelector('[name="' + field + '"]');
            var span = document.getElementById(FIELDS[field]);
            if (input) input.classList.add('is-invalid-gwb');
            if (span) { span.textContent = message; span.classList.remove('d-none'); }
        }

        function clearFieldErrors() {
            Object.keys(FIELDS).forEach(function (field) {
                var input = document.querySelector('[name="' + field + '"]');
                var span = document.getElementById(FIELDS[field]);
                if (input) input.classList.remove('is-invalid-gwb');
                if (span) { span.textContent = ''; span.classList.add('d-none'); }
            });
        }

        function setLoading(loading) {
            btn.disabled = loading;
            btn.innerHTML = loading
                ? '<i class="fa-solid fa-spinner fa-spin me-2"></i> Updating…'
                : '<i class="fa-solid fa-key me-2"></i> Change Password';
        }

        if (otpInput) {
            otpInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });
        }

        Object.keys(FIELDS).forEach(function (field) {
            var input = document.querySelector('[name="' + field + '"]');
            if (!input) return;
            input.addEventListener('input', function () {
                input.classList.remove('is-invalid-gwb');
                var span = document.getElementById(FIELDS[field]);
                if (span) { span.textContent = ''; span.classList.add('d-none'); }
            });
        });

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (otpExpiresAt && otpExpiresAt <= Date.now()) {
                markExpired();
                showToast('OTP has expired. Please request a new one.', 'error');
                return;
            }

            clearFieldErrors();

            var otp = otpInput.value.trim();
            var password = document.getElementById('password').value;
            var confirm = document.getElementById('password_confirmation').value;
            var valid = true;

            if (!/^\d{6}$/.test(otp)) {
                showFieldError('otp', 'OTP must be exactly 6 digits.');
                valid = false;
            }
            if (!password || password.length < 8) {
                showFieldError('password', 'Password must be at least 8 characters.');
                valid = false;
            }
            if (password !== confirm) {
                showFieldError('password_confirmation', 'Passwords do not match.');
                valid = false;
            }
            if (!valid) return;

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
                    showToast(data.message || 'Password changed!', 'success');
                    setTimeout(function () {
                        window.location.href = data.redirect || '/login';
                    }, 1200);
                    return;
                }

                if (data.otp_expired) {
                    markExpired();
                    showToast(data.message || 'OTP has expired. Please request a new one.', 'error');
                    setTimeout(function () {
                        window.location.href = data.redirect || forgotUrl;
                    }, 1800);
                    setLoading(false);
                    return;
                }

                if (data.errors && Object.keys(data.errors).length) {
                    Object.entries(data.errors).forEach(function (entry) {
                        showFieldError(entry[0], Array.isArray(entry[1]) ? entry[1][0] : entry[1]);
                    });
                    showToast(data.message || 'Unable to reset password.', 'error');
                } else {
                    showToast(data.message || 'Unable to reset password.', 'error');
                }

                setLoading(false);
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
                setLoading(false);
            }
        });
    })();
    </script>
</body>
</html>
