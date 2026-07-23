/* ==========================================================================
   Gym Website Builder — Auth Pages Scripts
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

  // Password show/hide toggle (works for any .toggle-password button)
  document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (!input) return;

      const icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Simple password strength meter (signup page)
  const strengthInput = document.getElementById('password');
  const strengthBar = document.getElementById('passwordStrengthBar');
  const strengthLabel = document.getElementById('passwordStrengthLabel');

  if (strengthInput && strengthBar && strengthLabel) {
    strengthInput.addEventListener('input', function () {
      const value = strengthInput.value;
      let score = 0;

      if (value.length >= 8) score++;
      if (/[A-Z]/.test(value)) score++;
      if (/[0-9]/.test(value)) score++;
      if (/[^A-Za-z0-9]/.test(value)) score++;

      const levels = [
        { width: '0%', color: 'var(--gwb-border)', label: 'Enter a password' },
        { width: '25%', color: '#ff6b6b', label: 'Weak' },
        { width: '50%', color: '#ffb020', label: 'Fair' },
        { width: '75%', color: '#ff8a3d', label: 'Good' },
        { width: '100%', color: '#3fd17d', label: 'Strong' },
      ];

      const level = value.length === 0 ? levels[0] : levels[score];
      strengthBar.style.width = level.width;
      strengthBar.style.background = level.color;
      strengthLabel.textContent = level.label;
    });
  }

  // Basic client-side confirm-password match feedback (signup page)
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('password_confirmation');
  const confirmFeedback = document.getElementById('confirmPasswordFeedback');

  if (password && confirmPassword && confirmFeedback) {
    const checkMatch = function () {
      if (confirmPassword.value.length === 0) {
        confirmFeedback.textContent = '';
        confirmPassword.classList.remove('is-invalid-gwb');
        return;
      }
      if (password.value !== confirmPassword.value) {
        confirmFeedback.textContent = 'Passwords do not match.';
        confirmPassword.classList.add('is-invalid-gwb');
      } else {
        confirmFeedback.textContent = '';
        confirmPassword.classList.remove('is-invalid-gwb');
      }
    };
    password.addEventListener('input', checkMatch);
    confirmPassword.addEventListener('input', checkMatch);
  }
});
