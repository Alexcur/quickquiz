@extends('layouts.guest')

@section('title', 'Sign in - Quickquiz')

@section('content')
<div class="auth">
    <aside class="auth-brand">
        <div class="brand">Quickquiz</div>
        <div>
            <h1>Exams that run themselves.</h1>
            <p>Server-side timer you can trust</p>
            <p>Randomized questions and options</p>
            <p>Automatic grading with partial credit</p>
        </div>
        <small>Student · Teacher</small>
    </aside>

    <main class="auth-form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h2>Sign in</h2>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@school.ma">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Your password">
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <button class="btn" type="submit">Sign in</button>
            <p class="hint"><a href="#">Forgot your password?</a></p>
            <p class="hint">No account yet? <a href="{{ route('register') }}">Create one</a></p>
        </form>
    </main>
</div>
@endsection