@php
    // Common values shared across ALL footer types
    $common = [
        'whatsapp' => '919999999999',
        'socials' => [
            ['icon' => 'fa-brands fa-facebook-f', 'href' => 'https://facebook.com', 'label' => 'Facebook'],
            ['icon' => 'fa-brands fa-instagram', 'href' => 'https://instagram.com', 'label' => 'Instagram'],
            ['icon' => 'fa-brands fa-youtube', 'href' => 'https://youtube.com', 'label' => 'YouTube'],
            ['icon' => 'fa-brands fa-linkedin-in', 'href' => 'https://linkedin.com', 'label' => 'LinkedIn'],
        ],
    ];

    // Values that differ per type
    $footerData = [
        'gsm' => [
            'brandHref' => route('home'),
            'brandName' => 'GYM<span>MANAGER</span>',
            'aboutText' => 'Cloud-hosted gym management software for memberships, trainers, classes, payments, and reports — run your gym from one dashboard on our secure servers.',
            'columns' => [
                ['title' => 'Quick Links', 'links' => [
                    ['label' => 'Features', 'href' => '#features'],
                    ['label' => 'How It Works', 'href' => '#how-it-works'],
                    ['label' => 'Pricing', 'href' => '#pricing'],
                    ['label' => 'FAQ', 'href' => '#faq'],
                ]],
                ['title' => 'Platform', 'links' => [
                    ['label' => 'Members', 'href' => '#features'],
                    ['label' => 'Trainers', 'href' => '#features'],
                    ['label' => 'Payments', 'href' => '#features'],
                    ['label' => 'Reports', 'href' => '#features'],
                ]],
                ['title' => 'Plans', 'links' => [
                    ['label' => 'Starter', 'href' => '#pricing'],
                    ['label' => 'Pro Gym', 'href' => '#pricing'],
                    ['label' => 'Multi-Branch', 'href' => '#pricing'],
                ]],
            ],
            'contactEmail' => 'hello@gymmanager.com',
            'contactEmailLabel' => null,
            'extraContact' => ['label' => 'Website Builder', 'href' => route('website-builder')],
            'copyrightName' => 'Gym Manager',
            'bottomTagline' => 'Hosted gym software for gyms that mean business.',
        ],

        'gwb' => [
            'brandHref' => url('/'),
            'brandName' => 'GYM<span>WEBSITE</span>BUILDER',
            'aboutText' => 'We build high-performance websites and digital growth systems for gyms, fitness trainers, and yoga studios — so you can spend less time on tech and more time on training your members.',
            'columns' => [
                ['title' => 'Quick Links', 'links' => [
                    ['label' => 'Why Choose Us', 'href' => '#why-us'],
                    ['label' => 'How It Works', 'href' => '#how-it-works'],
                    ['label' => 'Testimonials', 'href' => '#testimonials'],
                    ['label' => 'FAQ', 'href' => '#faq'],
                ]],
                ['title' => 'Services', 'links' => [
                    ['label' => 'Online Membership', 'href' => '#services'],
                    ['label' => 'Class Booking', 'href' => '#services'],
                    ['label' => 'Digital Marketing', 'href' => '#services'],
                    ['label' => 'Admin Dashboard', 'href' => '#services'],
                ]],
                ['title' => 'Pricing', 'links' => [
                    ['label' => 'Starter Website', 'href' => '#pricing'],
                    ['label' => 'Growth Website', 'href' => '#pricing'],
                    ['label' => 'Business Growth', 'href' => '#pricing'],
                ]],
            ],
            'contactEmail' => 'gymwebsitebuilder@gmail.com',
            'contactEmailLabel' => 'websitebuilder@gmail.com',
            'extraContact' => null,
            'copyrightName' => 'Gym Website Builder',
            'bottomTagline' => 'Built with <i class="fa-solid fa-bolt text-orange"></i> for gyms that mean business.',
        ],
    ];

    // Merge common + type-specific (type-specific wins on conflicts)
    $data = array_merge($common, $footerData[$type] ?? $footerData['gsm']);
@endphp

{{-- {{ strtoupper($type) }} footer --}}
<footer class="gwb-footer">
    <div class="container-gwb">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <a class="gwb-brand d-inline-block mb-3" href="{{ $data['brandHref'] }}">
                    {!! $data['brandName'] !!}
                </a>
                <p class="about-text">{{ $data['aboutText'] }}</p>
                <div class="footer-socials">
                    @foreach ($data['socials'] as $social)
                        <a href="{{ $social['href'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}">
                            <i class="{{ $social['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            @foreach ($data['columns'] as $col)
                <div class="col-lg-2 col-md-6 col-6">
                    <h6>{{ $col['title'] }}</h6>
                    <ul>
                        @foreach ($col['links'] as $link)
                            <li><a href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="col-lg-2 col-md-6 col-6">
                <h6>Contact</h6>
                <ul>
                    <li><a href="mailto:{{ $data['contactEmail'] }}"><i class="fa-solid fa-envelope me-1"></i> {{ $data['contactEmailLabel'] ?? $data['contactEmail'] }}</a></li>
                    <li><a href="https://wa.me/{{ $data['whatsapp'] }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Chat</a></li>
                    @if ($data['extraContact'])
                        <li><a href="{{ $data['extraContact']['href'] }}">{{ $data['extraContact']['label'] }}</a></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $data['copyrightName'] }}. All rights reserved.</span>
            <span>{!! $data['bottomTagline'] !!}</span>
        </div>
    </div>
</footer>

{{-- Floating buttons --}}
<a href="https://wa.me/919999999999" target="_blank" rel="noopener" class="whatsapp-float" aria-label="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="{{ asset('js/theme-toggle.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
