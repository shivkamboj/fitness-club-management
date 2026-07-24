<nav class="navbar navbar-expand-lg fixed-top gwb-navbar" id="gwbNavbar">
    <div class="container-gwb d-flex align-items-center justify-content-between">
        <a class="gwb-brand" href="{{ route('home') }}">
            GYM<span>MANAGER</span>
        </a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#gmsNavCollapse" aria-controls="gmsNavCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fa-lg text-white"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="gmsNavCollapse">
            <ul class="navbar-nav align-items-lg-center py-3 py-lg-0">
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="#faq">FAQ</a></li>
                <li class="nav-item"><a class="nav-link gwb-nav-link" href="{{ route('website-builder') }}">Website Builder</a></li>

                @auth
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-gwb-primary btn-gwb-sm">
                            Dashboard
                        </a>
                    </li>
                @endauth

                @guest
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-gwb-sm">
                            Login
                        </a>
                    </li>
                @endguest

                <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                    <a href="#pricing" class="btn btn-gwb-primary btn-gwb-sm">Buy Server</a>
                </li>

                <li class="nav-item mt-2 mt-lg-0 ms-lg-3">
                    <button type="button" class="theme-toggle-btn" aria-label="Toggle Light/Dark Theme">
                        <i class="fa-solid fa-sun"></i>
                        <span class="theme-toggle-label d-none d-sm-inline">Mode</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
