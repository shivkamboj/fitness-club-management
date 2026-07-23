/* ==========================================================================
   Gym Website Builder — Landing Page Scripts
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

  // Init AOS (Animate On Scroll)
  if (window.AOS) {
    AOS.init({
      duration: 700,
      easing: 'ease-out-cubic',
      once: true,
      offset: 80,
    });
  }

  // Sticky navbar background on scroll
  const navbar = document.getElementById('gwbNavbar');
  const toggleNavbarState = function () {
    if (window.scrollY > 40) {
      navbar.classList.add('is-scrolled');
    } else {
      navbar.classList.remove('is-scrolled');
    }
  };
  if (navbar) {
    toggleNavbarState();
    window.addEventListener('scroll', toggleNavbarState);
  }

  // Collapse mobile nav after clicking a link
  const navLinks = document.querySelectorAll('.gwb-nav-link');
  const navCollapse = document.getElementById('gwbNavCollapse');
  navLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      if (navCollapse && navCollapse.classList.contains('show')) {
        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(navCollapse);
        bsCollapse.hide();
      }
    });
  });

  // Back to top button
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 500) {
        backToTop.classList.add('show');
      } else {
        backToTop.classList.remove('show');
      }
    });
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Animated hero stat counters
  const counters = document.querySelectorAll('.hero-stat .num[data-count]');
  const animateCounter = function (el) {
    const target = parseInt(el.getAttribute('data-count'), 10);
    const suffix = el.getAttribute('data-suffix') || '';
    let current = 0;
    const step = Math.max(1, Math.ceil(target / 60));
    const tick = function () {
      current += step;
      if (current >= target) {
        el.textContent = target + suffix;
      } else {
        el.textContent = current + suffix;
        requestAnimationFrame(tick);
      }
    };
    tick();
  };

  if ('IntersectionObserver' in window && counters.length) {
    const observer = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (c) { observer.observe(c); });
  }

  // Contact form (demo submit handling — replace with real POST route in Blade)
  const contactForm = document.getElementById('gwbContactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const btn = contactForm.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending...';

      setTimeout(function () {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check me-2"></i> Message Sent';
        contactForm.reset();
        setTimeout(function () { btn.innerHTML = originalText; }, 2500);
      }, 1200);
    });
  }
});
