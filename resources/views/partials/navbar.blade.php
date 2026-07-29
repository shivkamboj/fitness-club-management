@php
    $type = $type ?? 'gsm'; // default to prevent "Undefined variable $type"

    $navData = [
        'gsm' => [
            'brandHref'   => route('home'),
            'brandName'   => 'GYM<span>MANAGER</span>',
            'brandLogo'   => asset('images/favicon-48x48.png'),
            'navId'       => 'gmsNavCollapse',
            'links' => [
                ['label' => 'Features', 'href' => '#features'],
                ['label' => 'Pricing', 'href' => '#pricing'],
                ['label' => 'How It Works', 'href' => '#how-it-works'],
                ['label' => 'FAQ', 'href' => '#faq'],
                ['label' => 'Website Builder', 'href' => route('website-builder')],
            ],
            'cta' => ['label' => 'Buy Server', 'href' => '#pricing'],
        ],

        'gwb' => [
            'brandHref'   => url('/'),
            'brandName'   => 'GYM<span>WEBSITE</span>BUILDER',
            'brandLogo'   => null,
            'navId'       => 'gwbNavCollapse',
            'links' => [
                ['label' => 'Why Us', 'href' => '#why-us'],
                ['label' => 'Pricing', 'href' => '#pricing'],
                ['label' => 'Services', 'href' => '#services'],
                ['label' => 'How It Works', 'href' => '#how-it-works'],
                ['label' => 'Gym Manager', 'href' => route('home')],
            ],
            'cta' => ['label' => 'Contact Us', 'href' => '#contact'],
        ],
    ];

    $data = $navData[$type] ?? $navData['gsm'];
@endphp

<nav class="navbar navbar-expand-lg fixed-top gwb-navbar" id="gwbNavbar">
    <div class="container-gwb d-flex align-items-center justify-content-between">
        <a class="gwb-brand d-flex align-items-center" href="{{ $data['brandHref'] }}">
            @if ($data['brandLogo'])
                <img src="{{ $data['brandLogo'] }}" alt="{{ strip_tags($data['brandName']) }} Logo" class="gwb-brand-logo">
            @endif
            {!! $data['brandName'] !!}
        </a>

        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#{{ $data['navId'] }}" aria-controls="{{ $data['navId'] }}"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fa-lg text-white"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="{{ $data['navId'] }}">
            <ul class="navbar-nav align-items-lg-center py-3 py-lg-0">
                @foreach ($data['links'] as $link)
                    <li class="nav-item"><a class="nav-link gwb-nav-link" href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
                @endforeach

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
                    <a href="{{ $data['cta']['href'] }}" class="btn btn-gwb-primary btn-gwb-sm">{{ $data['cta']['label'] }}</a>
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
