@extends('layouts.guest')

@section('title', 'Check your inbox - Quickquiz')

@section('content')
<div class="center-page">
    <div class="card">
        <div class="icon-circle">✉</div>
        <h1>Check your inbox</h1>
        <p class="lead">
            We sent a confirmation link to <strong>{{ auth()->user()->email }}</strong>.
            Open it to activate your account.
        </p>
        <p class="hint">The link expires in {{ config('auth.verification.expire') }} minutes.</p>
        @if (session('status') === 'verification-link-sent')
            <p class="success">A new confirmation email has been sent.</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn-outline" type="submit">Resend email</button>
        </form>

        <form method="POST" action="{{ route('verification.change') }}">
            @csrf
            <button class="link-button" type="submit">Use a different email</button>
        </form>
    </div>
</div>
@endsection