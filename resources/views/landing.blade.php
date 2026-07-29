@extends('partials.marketing', ['type' => 'gwb'])

@section('content')

    {{-- ============================= HERO ============================= --}}
    <header class="hero" id="hero">
        <video class="hero-video-bg" autoplay muted loop playsinline preload="auto" aria-hidden="true">
            <source src="{{ asset('videos/gym_website_hero_animation.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-video-overlay" aria-hidden="true"></div>
        <div class="container-gwb">
            <div class="row align-items-center">
                <div class="col-lg-7" data-aos="fade-up">
                    <span class="hero-badge"><span class="dot"></span> Trusted by 98+ Gyms &amp; Studios</span>
                    <h1>Build Your Professional <span class="gradient-text">Gym Website</span> in Just a Few Days</h1>
                    <p class="lead-gwb">
                        Get more members with a beautiful website, online membership, SEO,
                        and digital marketing — built specifically for gyms, trainers, and yoga studios.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#pricing" class="btn btn-gwb-primary">
                            <i class="fa-solid fa-dumbbell me-2"></i> View Plans
                        </a>
                        <a href="#contact" class="btn btn-gwb-outline">
                            <i class="fa-regular fa-comment-dots me-2"></i> Contact Us
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span class="num" data-count="98" data-suffix="+">0</span>
                            <span class="label">Gyms Launched</span>
                        </div>
                        <div class="hero-stat">
                            <span class="num" data-count="5" data-suffix=" Days">0</span>
                            <span class="label">Avg. Delivery</span>
                        </div>
                        <div class="hero-stat">
                            <span class="num" data-count="98" data-suffix="%">0</span>
                            <span class="label">Client Retention</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="stripe-divider"></div>

    {{-- ============================= WHY CHOOSE US ============================= --}}
    <section class="section" id="why-us">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Why Choose Us</span>
                <h2 class="section-title">Everything Your Gym Website Needs</h2>
                <p class="section-sub mx-auto">
                    Purpose-built features that help fitness businesses look professional,
                    load fast, and convert visitors into paying members.
                </p>
            </div>

            @php
                $whyChooseUs = [
                    ['icon' => 'fa-solid fa-mobile-screen-button', 'title' => 'Responsive Website', 'text' => 'Looks perfect on every screen size and device.'],
                    ['icon' => 'fa-solid fa-thumbs-up', 'title' => 'Mobile Friendly', 'text' => 'Optimized touch experience for on-the-go members.'],
                    ['icon' => 'fa-solid fa-bolt', 'title' => 'Fast Loading', 'text' => 'Speed-optimized pages that keep visitors engaged.'],
                    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Secure', 'text' => 'SSL, backups, and hardened hosting as standard.'],
                    ['icon' => 'fa-solid fa-id-card', 'title' => 'Online Membership', 'text' => 'Let members sign up and manage plans online.'],
                    ['icon' => 'fa-solid fa-magnifying-glass-chart', 'title' => 'SEO Ready', 'text' => 'Structured to rank on Google in your local area.'],
                    ['icon' => 'fa-solid fa-location-dot', 'title' => 'Google Maps', 'text' => 'Help members find your gym in seconds.'],
                    ['icon' => 'fa-brands fa-whatsapp', 'title' => 'WhatsApp Chat', 'text' => 'Instant chat button for quick member queries.'],
                    ['icon' => 'fa-solid fa-envelope-open-text', 'title' => 'Contact Form', 'text' => 'Capture leads directly from your website.'],
                    ['icon' => 'fa-solid fa-images', 'title' => 'Gallery', 'text' => 'Showcase your gym, equipment, and events.'],
                    ['icon' => 'fa-solid fa-person-running', 'title' => 'Trainer Profiles', 'text' => 'Highlight your coaches and their specialties.'],
                ];
            @endphp

            <div class="row g-4">
                @foreach($whyChooseUs as $i => $item)
                    <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 80 }}">
                        <x-feature-card :icon="$item['icon']" :title="$item['title']" :text="$item['text']" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= PRICING ============================= --}}
    <section class="section" id="pricing" style="background: var(--gwb-bg-soft);">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Pricing</span>
                <h2 class="section-title">Plans Built for Every Stage of Growth</h2>
                <p class="section-sub mx-auto">
                    Start with a one-time website, or subscribe for ongoing SEO and marketing
                    that keeps bringing in new members.
                </p>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <x-pricing-card
                        name="Starter Website"
                        price="258"
                        period="One Time"
                        button="Get Started"
                        href="#contact"
                        :features="[
                            ['text' => 'Professional Website'],
                            ['text' => 'Home Page'],
                            ['text' => 'About'],
                            ['text' => 'Services'],
                            ['text' => 'Gallery'],
                            ['text' => 'Contact Page'],
                            ['text' => 'WhatsApp Button'],
                            ['text' => 'Google Map'],
                            ['text' => 'Mobile Responsive'],
                            ['text' => '7 Days Support'],
                        ]"
                    />
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <x-pricing-card
                        name="Growth Website"
                        price="18"
                        period="/ month"
                        button="Start Subscription"
                        href="#contact"
                        badge="Most Popular"
                        :featured="true"
                        :features="[
                            ['text' => 'Everything in Starter', 'heading' => true],
                            ['text' => 'SEO Optimization'],
                            ['text' => 'Google Search Console'],
                            ['text' => 'Google Analytics'],
                            ['text' => 'Speed Optimization'],
                            ['text' => 'Blog Section'],
                            ['text' => 'Monthly Updates'],
                            ['text' => 'Technical Support'],
                        ]"
                    />
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <x-pricing-card
                        name="Business Growth"
                        price="44"
                        period="/ month"
                        button="Grow My Gym"
                        href="#contact"
                        badge="Best Value"
                        :features="[
                            ['text' => 'Everything in Growth', 'heading' => true],
                            ['text' => 'Digital Marketing'],
                            ['text' => 'Facebook Ads Support'],
                            ['text' => 'Instagram Marketing'],
                            ['text' => 'Lead Generation'],
                            ['text' => 'Google Business Profile'],
                            ['text' => 'Monthly Performance Report'],
                            ['text' => 'Unlimited Content Updates'],
                            ['text' => 'Priority Support'],
                        ]"
                    />
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= EXTRA SERVICES ============================= --}}
    <section class="section" id="services">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Extra Services</span>
                <h2 class="section-title">Power Up Your Gym Platform</h2>
                <p class="section-sub mx-auto">
                    Add-on modules that turn your website into a complete member management system.
                </p>
            </div>

            @php
                $extraServices = [
                    ['icon' => 'fa-solid fa-id-badge', 'title' => 'Online Membership System'],
                    ['icon' => 'fa-solid fa-calendar-check', 'title' => 'Class Booking'],
                    ['icon' => 'fa-solid fa-person-walking-arrow-right', 'title' => 'Personal Trainer Booking'],
                    ['icon' => 'fa-solid fa-credit-card', 'title' => 'Online Payments'],
                    ['icon' => 'fa-solid fa-utensils', 'title' => 'Diet Plan Portal'],
                    ['icon' => 'fa-solid fa-dumbbell', 'title' => 'Workout Plans'],
                    ['icon' => 'fa-solid fa-bell', 'title' => 'Push Notifications'],
                    ['icon' => 'fa-solid fa-gauge-high', 'title' => 'Admin Dashboard'],
                    ['icon' => 'fa-solid fa-mobile-screen', 'title' => 'Mobile App', 'soon' => true],
                ];
            @endphp

            <div class="row g-4">
                @foreach($extraServices as $i => $item)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 80 }}">
                        <x-service-card :icon="$item['icon']" :title="$item['title']" :soon="$item['soon'] ?? false" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= HOW IT WORKS ============================= --}}
    <section class="section" id="how-it-works" style="background: var(--gwb-bg-soft);">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">How It Works</span>
                <h2 class="section-title">Live in 4 Simple Steps</h2>
                <p class="section-sub mx-auto">From sign-up to launch, we handle the heavy lifting.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <x-step-card number="01" title="Choose Plan" text="Pick the website plan that fits your gym's stage and budget." />
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <x-step-card number="02" title="Share Your Gym Details" text="Send us your branding, photos, and service information." />
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <x-step-card number="03" title="Website Development" text="Our team designs and builds your site within days." />
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <x-step-card number="04" title="Website Goes Live" text="Review, approve, and launch to start attracting members." :last="true" />
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= TESTIMONIALS ============================= --}}
    <section class="section" id="testimonials">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Testimonials</span>
                <h2 class="section-title">Gym Owners Love the Results</h2>
            </div>

            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <x-testimonial-card
                                    name="Rohit Sharma"
                                    role="Owner, PowerHouse Gym"
                                    quote="Our new website brought in more membership inquiries in the first month than we had in the previous six."
                                    avatar="https://randomuser.me/api/portraits/men/32.jpg"
                                />
                            </div>
                            <div class="col-lg-4">
                                <x-testimonial-card
                                    name="Anita Desai"
                                    role="Founder, Prana Yoga Studio"
                                    quote="Booking classes online finally stopped the endless WhatsApp back-and-forth. Members love the simplicity."
                                    avatar="https://randomuser.me/api/portraits/women/44.jpg"
                                />
                            </div>
                            <div class="col-lg-4">
                                <x-testimonial-card
                                    name="Vikram Singh"
                                    role="Head Trainer, IronCore Fitness"
                                    quote="The SEO plan got us ranking on Google Maps within weeks — walk-ins have gone up noticeably."
                                    avatar="https://randomuser.me/api/portraits/men/67.jpg"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-nav-wrap">
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <i class="fa-solid fa-arrow-left text-white"></i>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <i class="fa-solid fa-arrow-right text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= FAQ ============================= --}}
    <section class="section" id="faq" style="background: var(--gwb-bg-soft);">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">FAQ</span>
                <h2 class="section-title">Frequently Asked Questions</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up">
                    <div class="accordion accordion-gwb" id="faqAccordion">
                        <x-faq-item
                            id="days"
                            question="How many days does it take?"
                            answer="Most gym websites are designed and delivered within 3 to 7 days, depending on the plan and how quickly content is shared with us."
                            :open="true"
                        />
                        <x-faq-item
                            id="upgrade"
                            question="Can I upgrade later?"
                            answer="Yes. You can move from the Starter Website to Growth or Business Growth at any time — we simply add the new features to your existing site."
                        />
                        <x-faq-item
                            id="hosting"
                            question="Do you provide hosting?"
                            answer="Yes, secure and fast hosting is included with every subscription plan. Hosting for the one-time Starter plan can be added separately."
                        />
                        <x-faq-item
                            id="cancel"
                            question="Can I cancel subscription?"
                            answer="Absolutely. Subscriptions are month-to-month with no long-term lock-in, and you can cancel anytime from your account or by contacting support."
                        />
                        <x-faq-item
                            id="redesign"
                            question="Do you redesign existing websites?"
                            answer="Yes, we can redesign and modernize your existing gym or studio website while keeping your current content and branding intact."
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= FINAL CTA ============================= --}}
    <section class="final-cta">
        <div class="container-gwb">
            <div data-aos="zoom-in">
                <h2>Ready to Grow Your <span class="gradient-text">Gym?</span></h2>
                <p>Start today and attract more members with a professional website built for results.</p>
                <div class="cta-buttons">
                    <a href="#pricing" class="btn btn-gwb-primary">
                        <i class="fa-solid fa-rocket me-2"></i> Start Now
                    </a>
                    <a href="#contact" class="btn btn-gwb-outline">
                        <i class="fa-solid fa-headset me-2"></i> Talk to Expert
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= CONTACT ============================= --}}
    <section class="section" id="contact">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Contact</span>
                <h2 class="section-title">Let's Build Your Gym Website</h2>
                <p class="section-sub mx-auto">Tell us about your gym and we'll get back within 24 hours.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="contact-card">
                        <form id="gwbContactForm" method="POST" action="{{ route('contact.store') ?? '#' }}">
                            @csrf

                            @if(session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label  small">Full Name</label>
                                    <input type="text" name="name" class="form-control form-control-gwb" placeholder="John Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label  small">Gym / Studio Name</label>
                                    <input type="text" name="gym_name" class="form-control form-control-gwb" placeholder="PowerHouse Gym">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label  small">Email</label>
                                    <input type="email" name="email" class="form-control form-control-gwb" placeholder="you@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label  small">Phone</label>
                                    <input type="tel" name="phone" class="form-control form-control-gwb" placeholder="+91 90000 00000" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label  small">Message</label>
                                    <textarea name="message" rows="4" class="form-control form-control-gwb" placeholder="Tell us about your gym and what you need..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-gwb-primary w-100">
                                        <i class="fa-solid fa-paper-plane me-2"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left">
                    {{-- <div class="contact-info-item">
                        <i class="fa-solid fa-phone"></i>
                        <div>
                            <div class="title">Call Us</div>
                            <div class="detail">+91 99999 99999</div>
                        </div>
                    </div> --}}
                    <div class="contact-info-item">
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <div class="title">Email Us</div>
                            <div class="detail">hello@gymwebsitebuilder.com</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fa-brands fa-whatsapp"></i>
                        <div>
                            <div class="title">WhatsApp</div>
                            <div class="detail">Chat with us instantly</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <div>
                            <div class="title">Location</div>
                            <div class="detail">Serving gyms &amp; studios pan-India, remotely.</div>
                        </div>
                    </div>

                    <div class="map-frame mt-4">
                        <iframe
                            src="https://maps.google.com/maps?q=chandigarh,%20India&t=&z=12&ie=UTF8&iwloc=&output=embed"
                            width="100%"
                            height="220"
                            style="border:0;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Chandigarh">
                        </iframe>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
