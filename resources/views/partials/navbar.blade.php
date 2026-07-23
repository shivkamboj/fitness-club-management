<nav class="navbar navbar-expand-lg fixed-top gwb-navbar" id="gwbNavbar">
    <div class="container-gwb d-flex align-items-center justify-content-between">
        <a class="gwb-brand" href="{{ url('/') }}">
            GYM<span>WEBSITE</span>BUILDER
        </a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#gwbNavCollapse" aria-controls="gwbNavCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fa-lg text-white"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="gwbNavCollapse">
            <ul class="navbar-nav align-items-lg-center py-3 py-lg-0">
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#why-us">Why Us</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#testimonials">Reviews</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#faq">FAQ</a></li>

                 @auth
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-gwb-primary btn-gwb-sm">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item mt-2 mt-lg-0 ms-lg-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-gwb-sm">
                                Logout
                            </button>
                        </form>
                    </li>
                @endauth

                @guest
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-gwb-sm">
                            Login
                        </a>
                    </li>

                    {{-- <li class="nav-item mt-2 mt-lg-0 ms-lg-2">
                        <a href="{{ route('register') }}"
                        class="btn btn-outline-light btn-gwb-sm">
                            Register
                        </a>
                    </li> --}}
                @endguest

                <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                    <a href="#contact" class="btn btn-gwb-primary btn-gwb-sm">Contact Us</a>
                </li>


            </ul>
        </div>
    </div>
</nav>
