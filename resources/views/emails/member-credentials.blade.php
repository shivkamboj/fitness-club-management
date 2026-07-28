<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $gym_name ?? 'GymForce' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #0f0f13;
            color: #e2e8f0;
            padding: 30px 16px;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #1a1a24 0%, #111118 100%);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px 16px 0 0;
            padding: 32px 40px;
            text-align: center;
            border-bottom: none;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }
        .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #ea580c, #f97316);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .brand-name {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .brand-name span { color: #f97316; }
        .header-tagline {
            font-size: 12px;
            color: #64748b;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        /* Divider */
        .divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, #ea580c, #f97316, transparent);
        }
        /* Body card */
        .body-card {
            background: #18181f;
            border: 1px solid rgba(255,255,255,.08);
            border-top: none;
            border-bottom: none;
            padding: 36px 40px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }
        .greeting span { color: #f97316; }
        .intro-text {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        /* Credentials box */
        .cred-box {
            background: #111118;
            border: 1px solid rgba(234,88,12,.25);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .cred-box-header {
            background: rgba(234,88,12,.12);
            border-bottom: 1px solid rgba(234,88,12,.2);
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #f97316;
        }
        .cred-row {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255,255,255,.05);
            gap: 12px;
        }
        .cred-row:last-child { border-bottom: none; }
        .cred-icon {
            width: 32px; height: 32px;
            background: rgba(234,88,12,.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        .cred-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            width: 80px;
            flex-shrink: 0;
        }
        .cred-value {
            font-size: 14px;
            color: #f1f5f9;
            font-weight: 600;
            font-family: 'Courier New', Courier, monospace;
            word-break: break-all;
        }
        .cred-value.password {
            color: #fbbf24;
            font-size: 16px;
            letter-spacing: 1px;
        }
        /* CTA Button */
        .cta-wrap { text-align: center; margin-bottom: 28px; }
        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #ea580c, #f97316);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .3px;
        }
        /* Tips */
        .tips-box {
            background: rgba(59,130,246,.08);
            border: 1px solid rgba(59,130,246,.2);
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .tips-box h4 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #60a5fa;
            margin-bottom: 10px;
        }
        .tip-item {
            font-size: 13px;
            color: #94a3b8;
            padding: 3px 0;
            line-height: 1.6;
        }
        .tip-item::before { content: '✓ '; color: #34d399; font-weight: bold; }
        /* Warning */
        .warning {
            font-size: 12px;
            color: #64748b;
            line-height: 1.7;
            text-align: center;
            margin-bottom: 8px;
        }
        /* Footer */
        .footer {
            background: #111118;
            border: 1px solid rgba(255,255,255,.08);
            border-top: none;
            border-radius: 0 0 16px 16px;
            padding: 24px 40px;
            text-align: center;
        }
        .footer-links {
            margin-bottom: 12px;
        }
        .footer-links a {
            color: #f97316;
            text-decoration: none;
            font-size: 13px;
            margin: 0 10px;
        }
        .footer-copy {
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
        }
        /* Mobile */
        @media only screen and (max-width: 480px) {
            .header, .body-card, .footer { padding: 24px 20px; }
            .cred-label { width: 60px; }
            .greeting { font-size: 20px; }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="brand">
            <div class="brand-icon">🏋️</div>
            <div class="brand-name">{{ $gym_name ?? 'GymForce' }}</div>
        </div>
        <div class="header-tagline">Member Welcome & Credentials</div>
    </div>

    <div class="divider"></div>

    <!-- Body -->
    <div class="body-card">

        <div class="greeting">
            Welcome aboard, <span>{{ $name ?? 'Member' }}!</span> 👋
        </div>
        <p class="intro-text">
            Your gym membership account has been successfully created at <strong style="color:#f97316;">{{ $gym_name ?? 'GymForce' }}</strong>.
            Below are your login credentials to access your personalized workout plans, diet programs, and membership details.
        </p>

        <!-- Credentials -->
        <div class="cred-box">
            <div class="cred-box-header">🔐 Your Login Credentials</div>
            <div class="cred-row">
                <div class="cred-icon">📧</div>
                <div class="cred-label">Email</div>
                <div class="cred-value">{{ $email ?? '—' }}</div>
            </div>
            <div class="cred-row">
                <div class="cred-icon">🔑</div>
                <div class="cred-label">Password</div>
                <div class="cred-value password">{{ $password ?? '—' }}</div>
            </div>
            <div class="cred-row">
                <div class="cred-icon">🌐</div>
                <div class="cred-label">Portal</div>
                <div class="cred-value">
                    <a href="{{ $login_url ?? url('/login') }}" style="color:#60a5fa; text-decoration:none;">
                        {{ $login_url ?? url('/login') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="cta-wrap">
            <a href="{{ $login_url ?? url('/login') }}" class="cta-btn">
                🚀 Login to Member Portal
            </a>
        </div>

        <!-- Tips -->
        <div class="tips-box">
            <h4>💡 Getting Started</h4>
            <div class="tip-item">Log in and explore your personalized workout plans</div>
            <div class="tip-item">Check your diet plan and daily nutrition targets</div>
            <div class="tip-item">Change your password after first login for security</div>
            <div class="tip-item">Contact your gym admin if you need any assistance</div>
        </div>

        <p class="warning">
            ⚠️ Please keep your credentials safe and do not share them with anyone.<br>
            If you did not register at {{ $gym_name ?? 'GymForce' }}, please ignore this email.
        </p>

    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-links">
            <a href="{{ url('/') }}">Homepage</a>
            <a href="{{ url('/login') }}">Login</a>
            <a href="{{ url('/contact-us') }}">Support</a>
        </div>
        <div class="footer-copy">
            &copy; {{ date('Y') }} {{ $gym_name ?? 'GymForce' }}. All rights reserved.<br>
            This is an automated email, please do not reply.
        </div>
    </div>

</div>
</body>
</html>
