@extends('emails.layouts.otp')

@section('content')
    <h1>Verify your email</h1>
    <p>Hi {{ $name }},</p>
    <p>
        Welcome to {{ config('app.name') }}! Use the one-time password below to verify your email address
        and activate your account.
    </p>
    <div class="otp">{{ $otp }}</div>
    <p>This OTP will expire in <strong>{{ $expiresInMinutes }} minutes</strong>.</p>
@endsection
