<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f4f5; font-family: Arial, Helvetica, sans-serif; color: #18181b; }
        .wrapper { width: 100%; padding: 32px 16px; }
        .card { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 32px; border: 1px solid #e4e4e7; }
        .brand { font-size: 18px; font-weight: 700; color: #ff5a1f; margin-bottom: 24px; }
        h1 { font-size: 22px; margin: 0 0 12px; }
        p { font-size: 15px; line-height: 1.6; color: #3f3f46; margin: 0 0 16px; }
        .otp { letter-spacing: 8px; font-size: 32px; font-weight: 700; text-align: center; background: #fff7ed; color: #c2410c; padding: 16px; border-radius: 10px; margin: 24px 0; }
        .note { font-size: 13px; color: #71717a; border-top: 1px solid #e4e4e7; padding-top: 16px; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand">{{ config('app.name') }}</div>
            @yield('content')
            <p class="note">
                If you did not request this email, you can safely ignore it. Your account remains secure.
            </p>
        </div>
    </div>
</body>
</html>
