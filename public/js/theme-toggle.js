/* ==========================================================================
   Gym Management Platform — Theme Toggle Manager (Dark / Light Mode)
   ========================================================================== */

(function () {
  'use strict';

  // Apply theme immediately to prevent FOUC (Flash of Unstyled Content)
  function getPreferredTheme() {
    const storedTheme = localStorage.getItem('gwb_theme');
    if (storedTheme) {
      return storedTheme;
    }
    return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
  }

  function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('gwb_theme', theme);
    updateToggleButtons(theme);
  }

  function updateToggleButtons(theme) {
    const buttons = document.querySelectorAll('.theme-toggle-btn');
    buttons.forEach(btn => {
      const icon = btn.querySelector('i');
      const label = btn.querySelector('.theme-toggle-label');
      
      if (theme === 'light') {
        btn.setAttribute('title', 'Switch to Dark Mode');
        btn.setAttribute('aria-label', 'Switch to Dark Mode');
        if (icon) {
          icon.className = 'fa-solid fa-moon';
        }
        if (label) {
          label.textContent = 'Dark Mode';
        }
      } else {
        btn.setAttribute('title', 'Switch to Light Mode');
        btn.setAttribute('aria-label', 'Switch to Light Mode');
        if (icon) {
          icon.className = 'fa-solid fa-sun';
        }
        if (label) {
          label.textContent = 'Light Mode';
        }
      }
    });
  }

  // Initial immediate application
  const currentTheme = getPreferredTheme();
  document.documentElement.setAttribute('data-theme', currentTheme);

  document.addEventListener('DOMContentLoaded', function () {
    updateToggleButtons(currentTheme);

    document.addEventListener('click', function (e) {
      const toggleBtn = e.target.closest('.theme-toggle-btn');
      if (toggleBtn) {
        e.preventDefault();
        const activeTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
      }
    });
  });

  // Export helper globally if needed
  window.gwbTheme = {
    getTheme: getPreferredTheme,
    setTheme: setTheme
  };
})();
