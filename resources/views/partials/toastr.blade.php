
{{-- Toastify CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<style>
    /* ── Toastify base overrides ─────────────────────────────────────────── */
    .toastify {
        padding: 14px 22px;
        border-radius: 12px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        backdrop-filter: blur(10px);
        min-width: 260px;
    }

    /* ── Type colour tokens ──────────────────────────────────────────────── */
    .toastify.on.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .toastify.on.error   { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .toastify.on.info    { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .toastify.on.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

    /* ── Mobile ──────────────────────────────────────────────────────────── */
    @media (max-width: 768px) {
        .toastify {
            max-width: calc(100vw - 40px) !important;
            min-width: unset !important;
            font-size: 13px !important;
            padding: 12px 18px !important;
            margin: 0 20px !important;
            border-radius: 10px !important;
        }
    }
</style>

{{-- Toastify JS --}}
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    /**
     * showToast(message, type)
     * ──────────────────────────────────────────────────────────────────────
     * Global helper available to any page that includes this partial.
     * type: 'success' | 'error' | 'info' | 'warning'
     */
    window.showToast = function (message, type) {
        type = type || 'success';

        const gradients = {
            success: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            error:   'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            info:    'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
            warning: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
        };

        // Truncate long messages on small screens
        var displayMessage = message;
        if (window.innerWidth <= 768 && message.length > 50) {
            displayMessage = message.substring(0, 50) + '…';
        }

        Toastify({
            text:        displayMessage,
            duration:    3500,
            close:       true,
            gravity:     'top',
            position:    'center',
            stopOnFocus: true,
            className:   type,
            style: {
                background:   gradients[type] || gradients.success,
                borderRadius: '12px',
                padding:      '14px 22px',
                fontWeight:   '500',
                boxShadow:    '0 8px 24px rgba(0,0,0,0.18)',
            },
            offset: { x: 0, y: window.innerWidth <= 768 ? 10 : 0 },
        }).showToast();
    };

    {{-- ── Auto-fire session flash messages ────────────────────────────── --}}
    @if (Session::has('success'))
        window.showToast(@json(Session::get('success')), 'success');
    @endif

    @if (Session::has('error'))
        window.showToast(@json(Session::get('error')), 'error');
    @endif

    @if (Session::has('info'))
        window.showToast(@json(Session::get('info')), 'info');
    @endif

    @if (Session::has('warning'))
        window.showToast(@json(Session::get('warning')), 'warning');
    @endif
</script>
