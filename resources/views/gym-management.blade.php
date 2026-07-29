@extends('partials.marketing', ['type' => 'gsm'])

@section('content')

    {{-- ============================= HERO ============================= --}}
    <header class="gym_manager hero" id="hero">
        <video class="hero-video-bg" autoplay muted loop playsinline preload="auto" aria-hidden="true">
            <source src="{{ asset('videos/gym_management_system_animation.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-video-overlay" aria-hidden="true"></div>
        <div class="container-gwb">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <span class="hero-badge"><span class="dot"></span> Trusted by 98+ Gyms &amp; Studios Worldwide</span>

                    <p class="gwb-brand mb-3" style="font-size: clamp(1.6rem, 3.2vw, 2.4rem);">
                        GYM<span> MANAGER</span>
                    </p>
                    <h1>
                        One Dashboard to <span class="gradient-text">Run, Grow &amp; Automate</span> Your Entire Gym
                    </h1>
                    <p class="lead-gwb">
                        Members, trainers, classes, payments, and reports — all synced in real time,
                        hosted securely on our cloud. No servers to buy. No IT team required.
                        Just log in and run your business.
                        <br>
                        Launch in Minutes on <span class="gradient-text">Our Cloud Server</span>
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#pricing" class="btn btn-gwb-primary">
                            <i class="fa-solid fa-server me-2"></i> Get Started Today
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-gwb-outline">
                            <i class="fa-solid fa-user-plus me-2"></i> Start Free 14-Day Trial
                        </a>
                    </div>

                    <div class="d-flex flex-wrap gap-4 mt-4 hero-trust-row">
                        <span><i class="fa-solid fa-check text-success me-1"></i> No setup fees</span>
                        <span><i class="fa-solid fa-check text-success me-1"></i> Cancel anytime</span>
                        <span><i class="fa-solid fa-check text-success me-1"></i> 24/7 support</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="stripe-divider"></div>

    {{-- ============================= FEATURES ============================= --}}
    <section class="section" id="features">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Platform Features</span>
                <h2 class="section-title">Everything to Run Your Gym</h2>
                <p class="section-sub mx-auto">
                    One cloud system for daily operations — from walk-in sign-ups to renewal reminders.
                </p>
            </div>

            @php
                $features = [
                    ['icon' => 'fa-solid fa-users', 'title' => 'Member Management', 'text' => 'Add members, track renewals, and manage attendance in seconds.'],
                    ['icon' => 'fa-solid fa-user-tie', 'title' => 'Trainer Profiles', 'text' => 'Assign trainers, specialties, and member relationships.'],
                    ['icon' => 'fa-solid fa-calendar-check', 'title' => 'Class Scheduling', 'text' => 'Create class slots, capacity limits, and bookings.'],
                    ['icon' => 'fa-solid fa-id-card', 'title' => 'Membership Plans', 'text' => 'Monthly, quarterly, and custom plans with auto status.'],
                    ['icon' => 'fa-solid fa-credit-card', 'title' => 'Payments & Billing', 'text' => 'Record payments, dues, and pending invoices clearly.'],
                    ['icon' => 'fa-solid fa-dumbbell', 'title' => 'Workout Plans', 'text' => 'Build and assign workout programs to members.'],
                    ['icon' => 'fa-solid fa-utensils', 'title' => 'Diet Plans', 'text' => 'Share nutrition plans from the same dashboard.'],
                    ['icon' => 'fa-solid fa-chart-line', 'title' => 'Reports & Insights', 'text' => 'Revenue, renewals, and growth at a glance.'],
                    ['icon' => 'fa-solid fa-cloud', 'title' => 'Cloud Hosting', 'text' => 'Your gym data stays on our secure managed servers.'],
                    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Secure Access', 'text' => 'Role-based login for owners and staff.'],
                    ['icon' => 'fa-solid fa-mobile-screen-button', 'title' => 'Works Everywhere', 'text' => 'Use it on desktop, tablet, or phone browser.'],
                    ['icon' => 'fa-solid fa-headset', 'title' => 'Setup Support', 'text' => 'We help you go live after you purchase a plan.'],
                ];
            @endphp

            <div class="row g-4">
                @foreach($features as $i => $item)
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
                <span class="section-eyebrow">Server Plans</span>
                <h2 class="section-title">Purchase Your Gym Server</h2>
                <p class="section-sub mx-auto">
                    Choose a hosted plan. We create your gym account on our servers —
                    you log in and start managing members the same day.
                </p>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <x-pricing-card
                        name="Starter"
                        price="29"
                        period="/ month"
                        button="Buy Starter Server"
                        href="{{ route('register') }}"
                        :features="[
                            ['text' => '1 Gym Branch'],
                            ['text' => 'Up to 150 Members'],
                            ['text' => 'Member & Plan Management'],
                            ['text' => 'Payment Tracking'],
                            ['text' => 'Basic Reports'],
                            ['text' => 'Cloud Hosting Included'],
                            ['text' => 'Email Support'],
                        ]"
                    />
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <x-pricing-card
                        name="Pro Gym"
                        price="59"
                        period="/ month"
                        button="Buy Pro Server"
                        href="{{ route('register') }}"
                        badge="Most Popular"
                        :featured="true"
                        :features="[
                            ['text' => 'Everything in Starter', 'heading' => true],
                            ['text' => 'Up to 500 Members'],
                            ['text' => 'Trainers & Classes'],
                            ['text' => 'Workout & Diet Plans'],
                            ['text' => 'Advanced Reports'],
                            ['text' => 'Priority Support'],
                            ['text' => 'Daily Backups'],
                        ]"
                    />
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <x-pricing-card
                        name="Multi-Branch"
                        price="119"
                        period="/ month"
                        button="Buy Enterprise"
                        href="#contact"
                        badge="Best for Chains"
                        :features="[
                            ['text' => 'Everything in Pro', 'heading' => true],
                            ['text' => 'Unlimited Members'],
                            ['text' => 'Multiple Branches'],
                            ['text' => 'Owner + Staff Roles'],
                            ['text' => 'Custom Onboarding'],
                            ['text' => 'Dedicated Support'],
                            ['text' => 'SLA & Priority Uptime'],
                        ]"
                    />
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= HOW IT WORKS ============================= --}}
    <section class="section" id="how-it-works">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">How It Works</span>
                <h2 class="section-title">Live on Our Servers in 4 Steps</h2>
                <p class="section-sub mx-auto">Buy a plan, we host everything — you run the gym.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <x-step-card number="01" title="Choose a Plan" text="Pick Starter, Pro, or Multi-Branch based on your gym size." />
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <x-step-card number="02" title="Register & Pay" text="Create your account and purchase the server plan that fits." />
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <x-step-card number="03" title="We Activate Hosting" text="Your gym workspace is provisioned on our secure cloud." />
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <x-step-card number="04" title="Start Managing" text="Log in, add members, plans, and payments — you're live." :last="true" />
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= WHY HOST WITH US ============================= --}}
    <section class="section" id="why-host" style="background: var(--gwb-bg-soft);">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Why Our Servers</span>
                <h2 class="section-title">Built for Gym Owners, Hosted for You</h2>
                <p class="section-sub mx-auto">
                    Skip local installs and IT maintenance. Purchase once a month and we keep the system online.
                </p>
            </div>

            @php
                $whyHost = [
                    ['icon' => 'fa-solid fa-bolt', 'title' => 'Instant Access', 'text' => 'Start using your dashboard as soon as your plan is active.'],
                    ['icon' => 'fa-solid fa-lock', 'title' => 'Secure Cloud', 'text' => 'Encrypted access and managed infrastructure for gym data.'],
                    ['icon' => 'fa-solid fa-arrows-rotate', 'title' => 'Auto Updates', 'text' => 'New features ship to your account without downtime on your side.'],
                    ['icon' => 'fa-solid fa-database', 'title' => 'Backups Included', 'text' => 'Regular backups so member and payment records stay safe.'],
                ];
            @endphp

            <div class="row g-4">
                @foreach($whyHost as $i => $item)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                        <x-feature-card :icon="$item['icon']" :title="$item['title']" :text="$item['text']" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= TESTIMONIALS ============================= --}}
    <section class="section" id="testimonials">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">Testimonials</span>
                <h2 class="section-title">Gyms That Switched to Our Server</h2>
            </div>

            <div class="row g-4" data-aos="fade-up">
                <div class="col-lg-4">
                    <x-testimonial-card
                        name="Rohit Sharma"
                        alt="Rohit Sharma"
                        title="Rohit Sharma"
                        role="Owner, PowerHouse Gym"
                        quote="We stopped chasing Excel sheets. Members, dues, and renewals are finally in one place — and we did not buy any office PC for it."
                        avatar="https://randomuser.me/api/portraits/men/32.jpg"
                    />
                </div>
                <div class="col-lg-4">
                    <x-testimonial-card
                        name="Anita Desai"
                        alt="Anita Desai"
                        title="Anita Desai"
                        role="Founder, Prana Fitness"
                        quote="Buying the Pro server was the easiest decision. Staff log in from the front desk and I check reports from home."
                        avatar="https://randomuser.me/api/portraits/women/44.jpg"
                    />
                </div>
                <div class="col-lg-4">
                    <x-testimonial-card
                        name="Vikram Singh"
                        alt="Vikram Singh"
                        title="Vikram Singh"
                        role="Director, IronCore Chain"
                        quote="Multi-branch hosting let us run three locations without hiring an IT person. Support got us live in a day."
                        avatar="https://randomuser.me/api/portraits/men/67.jpg"
                    />
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= FAQ ============================= --}}
    <section class="section" id="faq" style="background: var(--gwb-bg-soft);">
        <div class="container-gwb">
            <div class="text-center mx-auto mb-5" style="max-width: 640px;" data-aos="fade-up">
                <span class="section-eyebrow">FAQ</span>
                <h2 class="section-title">Server &amp; Plan Questions</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up">
                    <div class="accordion accordion-gwb" id="faqAccordion">
                        <x-faq-item
                            id="what-server"
                            question="What do I get when I buy a server plan?"
                            answer="You get a hosted gym management account on our cloud — member management, plans, payments, and the modules included in your tier. No local server install required."
                            :open="true"
                        />
                        <x-faq-item
                            id="how-fast"
                            question="How fast can I start after purchase?"
                            answer="Most gyms are activated the same day. Register, choose a plan, and we provision your workspace so you can log in and add members immediately."
                        />
                        <x-faq-item
                            id="upgrade"
                            question="Can I upgrade later?"
                            answer="Yes. Move from Starter to Pro or Multi-Branch anytime. Your member data stays — we just unlock higher limits and features."
                        />
                        <x-faq-item
                            id="data"
                            question="Is my gym data safe?"
                            answer="Yes. Accounts run on managed cloud infrastructure with secure login, role-based access, and regular backups on Pro and above."
                        />
                        <x-faq-item
                            id="cancel"
                            question="Can I cancel my subscription?"
                            answer="Plans are month-to-month. You can cancel anytime; your account remains active until the end of the paid period."
                        />
                        <x-faq-item
                            id="website"
                            question="Do you also build gym websites?"
                            answer="Yes. Need a marketing website plus management software? Visit our Website Builder page or ask us during onboarding."
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
                <h2>Ready to Host Your <span class="gradient-text">Gym Software?</span></h2>
                <p>Purchase a server plan today and start managing members on our cloud.</p>
                <div class="cta-buttons">
                    <a href="#pricing" class="btn btn-gwb-primary">
                        <i class="fa-solid fa-server me-2"></i> Buy Server Now
                    </a>
                    <a href="#contact" class="btn btn-gwb-outline">
                        <i class="fa-solid fa-headset me-2"></i> Talk to Sales
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
                <h2 class="section-title">Ask About a Server Plan</h2>
                <p class="section-sub mx-auto">Tell us your gym size — we will recommend the right hosting plan within 24 hours.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="contact-card">
                        <form id="gmsContactForm" method="POST" action="{{ route('contact.store') }}">
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
                                    <label class="form-label small">Full Name</label>
                                    <input type="text" name="name" class="form-control form-control-gwb" placeholder="John Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Gym / Studio Name</label>
                                    <input type="text" name="gym_name" class="form-control form-control-gwb" placeholder="PowerHouse Gym">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Email</label>
                                    <input type="email" name="email" class="form-control form-control-gwb" placeholder="you@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Phone</label>
                                    <input type="tel" name="phone" class="form-control form-control-gwb" placeholder="+91 90000 00000" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small">Message</label>
                                    <textarea name="message" rows="4" class="form-control form-control-gwb" placeholder="How many members / branches do you have? Which plan are you interested in?"></textarea>
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
                    <div class="contact-info-item">
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <div class="title">Email Us</div>
                            <div class="detail">hello@gymmanager.com</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fa-brands fa-whatsapp"></i>
                        <div>
                            <div class="title">WhatsApp</div>
                            <div class="detail">Chat about server plans instantly</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fa-solid fa-server"></i>
                        <div>
                            <div class="title">Hosting</div>
                            <div class="detail">Cloud servers managed for gym software</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <i class="fa-solid fa-globe"></i>
                        <div>
                            <div class="title">Also Need a Website?</div>
                            <div class="detail"><a href="{{ route('website-builder') }}" class="text-orange">Visit Gym Website Builder</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
