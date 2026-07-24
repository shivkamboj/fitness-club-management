@extends('emails.layouts.otp')

@section('content')
    <h1>Welcome to {{ $gymName }}</h1>
    <p>Hi {{ $trainerName }},</p>
    <p>
        Your trainer account has been created for <strong>{{ $gymName }}</strong>.
        Use the credentials below to sign in to your Trainer Dashboard.
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#fff7ed;border-radius:10px;margin:24px 0;">
        <tr>
            <td style="padding:20px;">
                <p style="margin:0 0 10px;font-size:14px;color:#71717a;">Login Email</p>
                <p style="margin:0 0 16px;font-size:16px;font-weight:700;color:#18181b;">{{ $email }}</p>

                <p style="margin:0 0 10px;font-size:14px;color:#71717a;">Temporary Password</p>
                <p style="margin:0;font-size:16px;font-weight:700;color:#c2410c;letter-spacing:1px;">{{ $password }}</p>
            </td>
        </tr>
    </table>

    <p style="text-align:center;margin:28px 0;">
        <a href="{{ $loginUrl }}"
           style="display:inline-block;background:#ff5a1f;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:10px;font-weight:700;font-size:15px;">
            Login to Trainer Dashboard
        </a>
    </p>

    <p>
        For your security, please change your password after your first login.
    </p>
@endsection
