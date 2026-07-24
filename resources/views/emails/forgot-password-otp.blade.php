@extends('emails.layouts.otp')

@section('content')
    <h1>Password reset OTP</h1>
    <p>Hi {{ $name }},</p>
    <p>
        We received a request to reset the password for your {{ config('app.name') }} account.
        Use the OTP below to continue.
    </p>
    <div class="otp">{{ $otp }}</div>
    <p>This OTP will expire in <strong>{{ $expiresInMinutes }} minutes</strong>.</p>
@endsection
