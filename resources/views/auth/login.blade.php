<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <a href="{{ url('/') }}" class="auth-back-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>

                <h1>Welcome Back</h1>
                <p class="auth-sub">Log in to manage your gym website, membership plans, and marketing dashboard.</p>

                @if(session('status'))
                    <div class="auth-alert success">
                        <i class="fa-solid fa-circle-check"></i> {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="auth-alert">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label-gwb">Email Address</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-envelope leading-icon"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="form-control form-control-gwb @error('email') is-invalid-gwb @enderror"
                                   placeholder="you@example.com" required autofocus>
                        </div>
                        @error('email')
                            <span class="invalid-feedback-gwb">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label-gwb">Password</label>
                        <div class="input-group-gwb">
                            <i class="fa-solid fa-lock leading-icon"></i>
                            <input type="password" id="password" name="password"
                                   class="form-control form-control-gwb @error('password') is-invalid-gwb @enderror"
                                   placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback-gwb">{{ $message }}</span>
                        @enderror
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

                    <button type="submit" class="btn btn-gwb-primary w-100">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Log In
                    </button>
                </form>

                <div class="auth-divider">or continue with</div>

                <div class="row g-2">
                    <div class="col-6">
                        <a href="#" class="btn-social"><i class="fa-brands fa-google"></i> Google</a>
                    </div>
                    <div class="col-6">
                        <a href="#" class="btn-social"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
                    </div>
                </div>

                <p class="auth-footer-note">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one now</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
