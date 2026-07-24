@extends('emails.layouts.otp')

@section('content')
    <h1>Your new verification OTP</h1>
    <p>Hi {{ $name }},</p>
    <p>
        You requested a new email verification code for your {{ config('app.name') }} account.
        Use the OTP below to complete verification.
    </p>
    <div class="otp">{{ $otp }}</div>
    <p>This OTP will expire in <strong>{{ $expiresInMinutes }} minutes</strong>.</p>
@endsection
