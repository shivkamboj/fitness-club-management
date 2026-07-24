<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account | Gym Website Builder</title>
    <meta name="description" content="Create your Gym Website Builder account to get started with a professional gym or studio website.">
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
    <div class="auth-page register-img">

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
                <p>"The SEO plan got us ranking on Google Maps within weeks — walk-ins have gone up noticeably."</p>
                <div class="who">— Vikram Singh, Head Trainer, IronCore Fitness</div>
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
        <div class="auth-form-panel register">
            <div class="auth-box auth-box-wide">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <a href="{{ url('/') }}" class="auth-back-link mb-0">
                        <i class="fa-solid fa-arrow-left"></i> Back to Home
                    </a>
                    <button type="button" class="theme-toggle-btn" aria-label="Toggle Light/Dark Theme">
                        <i class="fa-solid fa-sun"></i>
                        <span class="theme-toggle-label d-none d-sm-inline">Mode</span>
                    </button>
                </div>

                <h1>Create Your Account</h1>
                <p class="auth-sub">Sign up to start building your gym's professional website today.</p>

                {{-- ── Register Form ──────────────────────────────────────── --}}
                <form id="registerForm" method="POST" action="{{ route('register.submit') }}" novalidate>
                    @csrf

                    {{-- Row 1: Name + Gym Name --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label-gwb">Full Name</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-user leading-icon"></i>
                                <input type="text" id="name" name="name"
                                       class="form-control form-control-gwb"
                                       placeholder="John Doe" autocomplete="name" autofocus>
                            </div>
                            {{-- Inline field error --}}
                            <span class="invalid-feedback-gwb d-none" id="err-name"></span>
                        </div>

                        <div class="col-md-6">
                            <label for="gym_name" class="form-label-gwb">
                                Gym / Studio Name <span class="text-secondary">(optional)</span>
                            </label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-dumbbell leading-icon"></i>
                                <input type="text" id="gym_name" name="gym_name"
                                       class="form-control form-control-gwb"
                                       placeholder="PowerHouse Gym">
                            </div>
                            {{-- No required validation for optional field --}}
                        </div>
                    </div>

                    {{-- Row 2: Email + Phone --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label-gwb">Email Address</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-envelope leading-icon"></i>
                                <input type="email" id="email" name="email"
                                       class="form-control form-control-gwb"
                                       placeholder="you@example.com" autocomplete="email">
                            </div>
                            {{-- Inline field error --}}
                            <span class="invalid-feedback-gwb d-none" id="err-email"></span>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label-gwb">Phone Number</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-phone leading-icon"></i>
                                <input type="text" id="phone" name="phone"
                                       class="form-control form-control-gwb"
                                       placeholder="90000 00000" autocomplete="tel"
                                       maxlength="10"
                                        pattern="[0-9]{10}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    >


                            </div>
                            {{-- Inline field error --}}
                            <span class="invalid-feedback-gwb d-none" id="err-phone"></span>
                        </div>
                    </div>

                    {{-- Row 3: Password + Confirm --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label-gwb">Password</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-lock leading-icon"></i>
                                <input type="password" id="password" name="password"
                                       class="form-control form-control-gwb"
                                       placeholder="Create a password" autocomplete="new-password">
                                <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            {{-- Strength meter --}}
                            <div class="password-strength">
                                <div class="bar" id="passwordStrengthBar"></div>
                            </div>
                            <span class="password-strength-label" id="passwordStrengthLabel">
                                8+ characters, letters, numbers &amp; symbols
                            </span>
                            {{-- Inline field error --}}
                            <span class="invalid-feedback-gwb d-none" id="err-password"></span>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label-gwb">Confirm Password</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-lock leading-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control form-control-gwb"
                                       placeholder="Re-enter your password" autocomplete="new-password">
                                <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Show password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            {{-- Inline field error (also used for mismatch) --}}
                            <span class="invalid-feedback-gwb d-none" id="err-password_confirmation"></span>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div class="mb-4">
                        <label class="form-check-gwb">
                            <input type="checkbox" id="terms" name="terms">
                            I agree to the <a href="#" class="auth-forgot">Terms of Service</a> and
                            <a href="#" class="auth-forgot">Privacy Policy</a>
                        </label>
                        {{-- Inline field error --}}
                        <span class="invalid-feedback-gwb d-none" id="err-terms"></span>
                    </div>

                    <button type="submit" id="registerBtn" class="btn btn-gwb-primary w-100">
                        <i class="fa-solid fa-user-plus me-2"></i> Create Account
                    </button>
                </form>

                <div class="auth-divider">or sign up with</div>

                <div class="row g-2">
                    <div class="col-6">
                        <a href="#" class="btn-social"><i class="fa-brands fa-google"></i> Google</a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn-social"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
                    </div>
                </div>

                <p class="auth-footer-note">
                    Already have an account?
                    <a href="{{ route('login') }}">Log in</a>
                </p>
            </div>
        </div>
    </div>

    {{-- Toastr: CSS + JS + session flash auto-fire --}}
    @include('partials.toastr')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Shared UI utilities: password toggle + strength meter (no AJAX logic) --}}
    <script src="{{ asset('js/auth.js') }}"></script>

    {{-- ═══════════════════════════════════════════════════════════════════════
         REGISTER PAGE — Inline AJAX + Inline Validation
         Scoped entirely to this page. No global dependencies except showToast.
    ═══════════════════════════════════════════════════════════════════════════ --}}

    <script>
(function () {
    'use strict';

    const form = document.getElementById('registerForm');
    const btn = document.getElementById('registerBtn');

    const FIELDS = {
        name: 'err-name',
        email: 'err-email',
        phone: 'err-phone',
        password: 'err-password',
        password_confirmation: 'err-password_confirmation',
        terms: 'err-terms'
    };

    function showFieldError(field, message) {
        const input = document.querySelector('[name="' + field + '"]');
        const span = document.getElementById(FIELDS[field]);

        if (input) input.classList.add('is-invalid-gwb');

        if (span) {
            span.innerHTML = message;
            span.classList.remove('d-none');
        }
    }

    function clearFieldError(field) {
        const input = document.querySelector('[name="' + field + '"]');
        const span = document.getElementById(FIELDS[field]);

        if (input) input.classList.remove('is-invalid-gwb');

        if (span) {
            span.innerHTML = '';
            span.classList.add('d-none');
        }
    }

    function clearAllErrors() {
        Object.keys(FIELDS).forEach(function (field) {
            clearFieldError(field);
        });
    }

    function setLoading(status) {

        if (status) {
            btn.disabled = true;
            btn.innerHTML =
                '<i class="fa fa-spinner fa-spin me-2"></i>Creating Account...';
        } else {
            btn.disabled = false;
            btn.innerHTML =
                '<i class="fa fa-user-plus me-2"></i>Create Account';
        }
    }

    function validateBeforeSubmit() {

        clearAllErrors();

        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const phone = document.getElementById('phone');
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const terms = document.getElementById('terms');

        // Name
        if (name.value.trim() == '') {
            showFieldError('name', 'Full name is required.');
            name.focus();
            return false;
        }

        // Email
        if (email.value.trim() == '') {
            showFieldError('email', 'Email address is required.');
            email.focus();
            return false;
        }

        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email.value.trim())) {
            showFieldError('email', 'Enter a valid email.');
            email.focus();
            return false;
        }

        // Phone
        if (phone.value.trim() == '') {
            showFieldError('phone', 'Phone number is required.');
            phone.focus();
            return false;
        }

        // Password
        if (password.value == '') {
            showFieldError('password', 'Password is required.');
            password.focus();
            return false;
        }

        if (password.value.length < 8) {
            showFieldError('password', 'Password must be at least 8 characters.');
            password.focus();
            return false;
        }

        // Confirm Password
        if (confirm.value == '') {
            showFieldError('password_confirmation', 'Confirm password is required.');
            confirm.focus();
            return false;
        }

        if (password.value != confirm.value) {
            showFieldError('password_confirmation', 'Passwords do not match.');
            confirm.focus();
            return false;
        }

        // Terms
        if (!terms.checked) {
            showFieldError('terms', 'Please accept Terms & Conditions.');
            terms.focus();
            return false;
        }

        return true;
    }

    // Remove error while typing
    Object.keys(FIELDS).forEach(function (field) {

        let input = document.querySelector('[name="' + field + '"]');

        if (!input) return;

        input.addEventListener('input', function () {
            clearFieldError(field);
        });

        input.addEventListener('change', function () {
            clearFieldError(field);
        });

    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!validateBeforeSubmit()) {
            return;
        }

        setLoading(true);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });
            const data = await response.json();
            setLoading(false);
            if (response.ok && data.success) {
                showToast(data.message || 'Account created successfully.', 'success');
                setTimeout(function () {
                    window.location.href = data.redirect || '/dashboard';
                }, 1000);
                return;
            }
            if (data.errors) {
                let firstInput = null;
                Object.keys(data.errors).forEach(function (field) {
                    showFieldError(field, data.errors[field][0]);
                    if (!firstInput) {
                        firstInput = document.querySelector('[name="' + field + '"]');
                    }
                });
                if (firstInput) {
                    firstInput.focus();
                }
            } else {
                showToast(data.message || 'Something went wrong.', 'error');
            }
        } catch (e) {
            setLoading(false);
            showToast('Network error. Please try again.', 'error');
            console.log(e);
        }

    });

})();
</script>

    {{-- <script>
    (function () {
        'use strict';

        /* ── Field registry: maps server field name → error span id ───────── */
        var FIELDS = {
            name:                  'err-name',
            email:                 'err-email',
            phone:                 'err-phone',
            password:              'err-password',
            password_confirmation: 'err-password_confirmation',
            terms:                 'err-terms',
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

        /** Clear all inline field errors */
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
                ? '<i class="fa-solid fa-spinner fa-spin me-2"></i> Creating account…'
                : '<i class="fa-solid fa-user-plus me-2"></i> Create Account';
        }

        /* ── Client-side pre-validation ───────────────────────────────────── */
        function validateBeforeSubmit() {
            clearFieldErrors();
            var valid = true;

            var name     = document.getElementById('name').value.trim();
            var email    = document.getElementById('email').value.trim();
            var phone    = document.getElementById('phone').value.trim();
            var password = document.getElementById('password').value;
            var confirm  = document.getElementById('password_confirmation').value;
            var terms    = document.getElementById('terms').checked;

            if (!name) {
                showFieldError('name', 'Full name is required.');
                valid = false;
            }

            if (!email) {
                showFieldError('email', 'Email address is required.');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showFieldError('email', 'Please enter a valid email address.');
                valid = false;
            }

            if (!phone) {
                showFieldError('phone', 'Phone number is required.');
                valid = false;
            }

            if (!password) {
                showFieldError('password', 'Password is required.');
                valid = false;
            } else if (password.length < 8) {
                showFieldError('password', 'Password must be at least 8 characters.');
                valid = false;
            }

            if (!confirm) {
                showFieldError('password_confirmation', 'Please confirm your password.');
                valid = false;
            } else if (password && password !== confirm) {
                showFieldError('password_confirmation', 'Passwords do not match.');
                valid = false;
            }

            if (!terms) {
                showFieldError('terms', 'You must agree to the Terms of Service.');
                valid = false;
            }

            return valid;
        }

        /* ── AJAX Submit ──────────────────────────────────────────────────── */
        var form = document.getElementById('registerForm');
        var btn  = document.getElementById('registerBtn');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            /* 1. Client-side check first — instant feedback, no server round-trip */
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
                    showToast(data.message || 'Account created!', 'success');
                    setTimeout(function () {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1200);

                } else {
                    /* ❌ Server returned field-level errors — show each inline */
                    if (data.errors && Object.keys(data.errors).length) {
                        Object.entries(data.errors).forEach(function (entry) {
                            var field    = entry[0];
                            var messages = entry[1];
                            showFieldError(field, Array.isArray(messages) ? messages[0] : messages);
                        });
                        /* Scroll to the first errored field */
                        var firstErr = form.querySelector('.is-invalid-gwb');
                        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        /* General / unexpected error */
                        showToast(data.message || 'Something went wrong.', 'error');
                    }

                    setLoading(btn, false);
                }

            } catch (err) {
                showToast('Network error. Please check your connection.', 'error');
                setLoading(btn, false);
            }
        });

        /* ── Clear field error as the user fixes each input ──────────────── */
        Object.keys(FIELDS).forEach(function (field) {
            var input = document.querySelector('[name="' + field + '"]');
            if (!input) return;
            input.addEventListener('input', function () {
                input.classList.remove('is-invalid-gwb');
                var span = document.getElementById(FIELDS[field]);
                if (span) { span.textContent = ''; span.classList.add('d-none'); }
            });
            /* Also clear on change for checkbox */
            input.addEventListener('change', function () {
                input.classList.remove('is-invalid-gwb');
                var span = document.getElementById(FIELDS[field]);
                if (span) { span.textContent = ''; span.classList.add('d-none'); }
            });
        });

    })();
    </script> --}}

    <script src="{{ asset('js/theme-toggle.js') }}"></script>
</body>
</html>
