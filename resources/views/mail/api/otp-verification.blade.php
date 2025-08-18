@extends('mail.api.layouts.email')

@section('content')
<tr>
    <td style=" padding:15px 0 0">
        <div style="text-align:left">
            Hello {{ $notifiable->first_name }} {{ $notifiable->last_name }},<br><br>
            <span style="display: block;">Thank you for signing up for {{ config('app.name') }}! To complete your registration, please use the following 4-digit code to verify your email address:</span>
        </div>
        <br>
        <span style="display: block;">Please find your verification code below:</span>
        <span style="display: block; font-size:30px; font-weight: bold;">{{ $otp }}</span>
        
        <br>
        <span style="display: block;">Please enter this code on the verification page to activate your account. This code will expire in {{ $expire }} minutes, so be sure to use it soon!</span>
        <br>
        <span style="display: block;">If you didn't sign up for a {{ config('app.name') }} account, please ignore this email or contact our support team if you have any concerns.</span>
        <br>
        <span style="display: block;">Thank you for choosing {{ config('app.name') }}!</span>
        <br>
        <span style="font-weight: 700;">{{ ucfirst(config('app.name')) }} Team</span>
    </td>
</tr>
@endsection