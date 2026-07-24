<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email | Gym Website Builder</title>
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
                <p>"Verify once, then manage your gym website, memberships, and marketing from one place."</p>
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

                <h1>Verify Your Email</h1>
                <p class="auth-sub">
                    Enter the 6-digit OTP sent to
                    <strong>{{ $email }}</strong>. It expires in 10 minutes.
                </p>

                <form id="verifyOtpForm" method="POST" action="{{ route('otp.verify.submit') }}" novalidate>
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <label for="otp" class="form-label-gwb">OTP Code</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-shield-halved leading-icon"></i>
                            <input type="text" id="otp" name="otp"
                                   class="form-control form-control-gwb"
                                   placeholder="123456" maxlength="6" inputmode="numeric"
                                   autocomplete="one-time-code" autofocus>
                        </div>
                        <span class="invalid-feedback-gwb d-none" id="err-otp"></span>
                    </div>

                    <button type="submit" id="verifyBtn" class="btn btn-gwb-primary w-100">
                        <i class="fa-solid fa-check me-2"></i> Verify Email
                    </button>
                </form>

                <div class="otp-resend-box mt-4">
                    <p class="auth-footer-note mb-2">Didn't get the code?</p>

                    <div id="resendCountdownWrap" class="otp-countdown-wrap">
                        <div class="otp-countdown-label">You can resend OTP in</div>
                        <div id="resendTimerDisplay" class="otp-countdown-time">05:00</div>
                        <div class="otp-countdown-bar">
                            <div id="resendProgressBar" class="otp-countdown-progress"></div>
                        </div>
                    </div>

                    <button type="button" id="resendOtpBtn" class="btn btn-gwb-outline w-100 resend-otp-btn is-disabled" disabled>
                        <i class="fa-solid fa-rotate-right me-2"></i>
                        <span id="resendBtnLabel">Resend OTP</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
    <style>
        .otp-resend-box {
            text-align: center;
        }
        .otp-countdown-wrap {
            background: var(--gwb-orange-dim, rgba(255, 90, 31, 0.12));
            border: 1px solid rgba(255, 90, 31, 0.28);
            border-radius: 12px;
            padding: 1rem 1.1rem 1.15rem;
            margin-bottom: 0.9rem;
        }
        .otp-countdown-wrap.is-ready {
            background: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.35);
        }
        .otp-countdown-label {
            color: var(--gwb-text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
        }
        .otp-countdown-time {
            font-family: var(--font-mono, 'JetBrains Mono', monospace);
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--gwb-orange-light, #ff8a3d);
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }
        .otp-countdown-wrap.is-ready .otp-countdown-time {
            color: #22c55e;
            font-size: 1.15rem;
            letter-spacing: 0.02em;
        }
        .otp-countdown-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            overflow: hidden;
        }
        [data-theme="light"] .otp-countdown-bar {
            background: rgba(15, 23, 42, 0.08);
        }
        .otp-countdown-progress {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #ff8a3d, #ff5a1f);
            border-radius: inherit;
            transition: width 1s linear;
        }
        .otp-countdown-wrap.is-ready .otp-countdown-progress {
            width: 0% !important;
            background: #22c55e;
        }
        .resend-otp-btn.is-disabled,
        .resend-otp-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }
    </style>
    <script>
    (function () {
        'use strict';

        var RESEND_COOLDOWN_MS = 5 * 60 * 1000; // 5 minutes
        var email = @json($email);
        var storageKey = 'otp_resend_until_' + email;
        var form = document.getElementById('verifyOtpForm');
        var btn = document.getElementById('verifyBtn');
        var resendBtn = document.getElementById('resendOtpBtn');
        var resendBtnLabel = document.getElementById('resendBtnLabel');
        var countdownWrap = document.getElementById('resendCountdownWrap');
        var timerDisplay = document.getElementById('resendTimerDisplay');
        var progressBar = document.getElementById('resendProgressBar');
        var countdownLabel = countdownWrap.querySelector('.otp-countdown-label');
        var otpInput = document.getElementById('otp');
        var errOtp = document.getElementById('err-otp');
        var timerInterval = null;
        var cooldownTotalMs = RESEND_COOLDOWN_MS;

        function showOtpError(message) {
            otpInput.classList.add('is-invalid-gwb');
            errOtp.textContent = message;
            errOtp.classList.remove('d-none');
        }

        function clearOtpError() {
            otpInput.classList.remove('is-invalid-gwb');
            errOtp.textContent = '';
            errOtp.classList.add('d-none');
        }

        function setLoading(loading) {
            btn.disabled = loading;
            btn.innerHTML = loading
                ? '<i class="fa-solid fa-spinner fa-spin me-2"></i> Verifying…'
                : '<i class="fa-solid fa-check me-2"></i> Verify Email';
        }

        function formatTime(ms) {
            var totalSec = Math.max(0, Math.ceil(ms / 1000));
            var mins = Math.floor(totalSec / 60);
            var secs = totalSec % 60;
            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        function getUntil() {
            var raw = sessionStorage.getItem(storageKey);
            return raw ? parseInt(raw, 10) : 0;
        }

        function setUntil(timestamp) {
            sessionStorage.setItem(storageKey, String(timestamp));
        }

        function enableResend() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            resendBtn.disabled = false;
            resendBtn.classList.remove('is-disabled');
            resendBtnLabel.textContent = 'Resend OTP Now';
            countdownWrap.classList.add('is-ready');
            countdownLabel.textContent = 'OTP ready to resend';
            timerDisplay.textContent = 'Ready';
            progressBar.style.width = '0%';
            sessionStorage.removeItem(storageKey);
        }

        function tick() {
            var remaining = getUntil() - Date.now();
            if (remaining <= 0) {
                enableResend();
                return;
            }

            resendBtn.disabled = true;
            resendBtn.classList.add('is-disabled');
            resendBtnLabel.textContent = 'Please wait…';
            countdownWrap.classList.remove('is-ready');
            countdownLabel.textContent = 'You can resend OTP in';
            timerDisplay.textContent = formatTime(remaining);

            var pct = Math.max(0, Math.min(100, (remaining / cooldownTotalMs) * 100));
            progressBar.style.width = pct + '%';
        }

        function startResendCooldown(fromNow, totalMs) {
            if (fromNow) {
                cooldownTotalMs = totalMs || RESEND_COOLDOWN_MS;
                setUntil(Date.now() + cooldownTotalMs);
            } else if (totalMs) {
                cooldownTotalMs = totalMs;
            } else {
                // Resume existing timer; keep full 5-min scale for the progress bar
                cooldownTotalMs = RESEND_COOLDOWN_MS;
            }

            if (timerInterval) clearInterval(timerInterval);
            tick();
            timerInterval = setInterval(tick, 1000);
        }

        // OTP was just sent — start or resume the visible 5-minute timer
        if (!getUntil() || getUntil() <= Date.now()) {
            startResendCooldown(true);
        } else {
            startResendCooldown(false);
        }

        otpInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
            clearOtpError();
        });

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearOtpError();

            if (!/^\d{6}$/.test(otpInput.value)) {
                showOtpError('OTP must be exactly 6 digits.');
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
                    sessionStorage.removeItem(storageKey);
                    showToast(data.message || 'Email verified!', 'success');
                    setTimeout(function () {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1000);
                } else {
                    if (data.errors && data.errors.otp) {
                        showOtpError(Array.isArray(data.errors.otp) ? data.errors.otp[0] : data.errors.otp);
                    } else {
                        showToast(data.message || 'Verification failed.', 'error');
                    }
                    setLoading(false);
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
                setLoading(false);
            }
        });

        resendBtn.addEventListener('click', async function (e) {
            e.preventDefault();

            if (resendBtn.disabled || getUntil() > Date.now()) {
                return;
            }

            resendBtn.disabled = true;
            resendBtn.classList.add('is-disabled');
            resendBtnLabel.textContent = 'Sending…';

            try {
                var response = await fetch(@json(route('otp.resend')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email: email }),
                });

                var data = await response.json();
                showToast(data.message || (data.success ? 'OTP resent.' : 'Failed to resend.'), data.success ? 'success' : 'error');

                if (data.success) {
                    startResendCooldown(true);
                } else if (data.retry_after) {
                    var retryMs = data.retry_after * 1000;
                    setUntil(Date.now() + retryMs);
                    startResendCooldown(false, retryMs);
                } else {
                    enableResend();
                }
            } catch (err) {
                showToast('Network error. Please try again.', 'error');
                enableResend();
            }
        });
    })();
    </script>
</body>
</html>
