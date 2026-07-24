<footer class="gwb-footer">
    <div class="container-gwb">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <a class="gwb-brand d-inline-block mb-3" href="{{ route('home') }}">
                    GYM<span>MANAGER</span>
                </a>
                <p class="about-text">
                    Cloud-hosted gym management software for memberships, trainers, classes,
                    payments, and reports — run your gym from one dashboard on our secure servers.
                </p>
                <div class="footer-socials">
                    <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6>Quick Links</h6>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6>Platform</h6>
                <ul>
                    <li><a href="#features">Members</a></li>
                    <li><a href="#features">Trainers</a></li>
                    <li><a href="#features">Payments</a></li>
                    <li><a href="#features">Reports</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6>Plans</h6>
                <ul>
                    <li><a href="#pricing">Starter</a></li>
                    <li><a href="#pricing">Pro Gym</a></li>
                    <li><a href="#pricing">Multi-Branch</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6>Contact</h6>
                <ul>
                    <li><a href="mailto:hello@gymmanager.com"><i class="fa-solid fa-envelope me-1"></i> hello@gymmanager.com</a></li>
                    <li><a href="https://wa.me/919999999999" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Chat</a></li>
                    <li><a href="{{ route('website-builder') }}">Website Builder</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Gym Manager. All rights reserved.</span>
            <span>Hosted gym software for gyms that mean business.</span>
        </div>
    </div>
</footer>
