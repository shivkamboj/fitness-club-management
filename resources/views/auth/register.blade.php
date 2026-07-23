<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <a href="{{ url('/') }}" class="auth-back-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>

                <h1>Create Your Account</h1>
                <p class="auth-sub">Sign up to start building your gym's professional website today.</p>

                @if($errors->any())
                    <div class="auth-alert">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label-gwb">Full Name</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-user leading-icon"></i>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       class="form-control form-control-gwb @error('name') is-invalid-gwb @enderror"
                                       placeholder="John Doe" required autofocus>
                            </div>
                            @error('name')
                                <span class="invalid-feedback-gwb">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="gym_name" class="form-label-gwb">Gym / Studio Name <span class="text-secondary">(optional)</span></label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-dumbbell leading-icon"></i>
                                <input type="text" id="gym_name" name="gym_name" value="{{ old('gym_name') }}"
                                       class="form-control form-control-gwb"
                                       placeholder="PowerHouse Gym">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label-gwb">Email Address</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-envelope leading-icon"></i>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="form-control form-control-gwb @error('email') is-invalid-gwb @enderror"
                                       placeholder="you@example.com" required>
                            </div>
                            @error('email')
                                <span class="invalid-feedback-gwb">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label-gwb">Phone Number</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-phone leading-icon"></i>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="form-control form-control-gwb @error('phone') is-invalid-gwb @enderror"
                                       placeholder="+91 90000 00000" required>
                            </div>
                            @error('phone')
                                <span class="invalid-feedback-gwb">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label-gwb">Password</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-lock leading-icon"></i>
                                <input type="password" id="password" name="password"
                                       class="form-control form-control-gwb @error('password') is-invalid-gwb @enderror"
                                       placeholder="Create a password" required>
                                <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="bar" id="passwordStrengthBar"></div>
                            </div>
                            <span class="password-strength-label" id="passwordStrengthLabel">8+ characters, letters, numbers &amp; symbols</span>
                            @error('password')
                                <span class="invalid-feedback-gwb">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label-gwb">Confirm Password</label>
                            <div class="input-group-gwb">
                                <i class="fa-solid fa-lock leading-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control form-control-gwb"
                                       placeholder="Re-enter your password" required>
                                <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Show password">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <span class="invalid-feedback-gwb" id="confirmPasswordFeedback"></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-check-gwb">
                            <input type="checkbox" name="terms" required>
                            I agree to the <a href="#" class="auth-forgot">Terms of Service</a> and <a href="#" class="auth-forgot">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-gwb-primary w-100">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
