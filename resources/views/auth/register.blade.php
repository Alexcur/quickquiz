@extends('layouts.guest')

@section('title', 'Create account - Quickquiz')

@section('content')
<div class="auth">
    <aside class="auth-brand">
        <div class="brand">Quickquiz</div>
        <div>
            <h1>Create your free account.</h1>
            <p>Teachers: build quizzes and see statistics</p>
            <p>Students: join with a link and see your result</p>
        </div>
        <small>Free for every teacher in the world</small>
    </aside>

    <main class="auth-form">
        <form id="register-form" method="POST" action="{{ route('register') }}">
            @csrf
            <h2>Create account</h2>

            <div class="segmented">
                <label>
                    <input type="radio" name="role" value="teacher" @checked(old('role', 'teacher') === 'teacher')>
                    <span>I am a teacher</span>
                </label>
                <label>
                    <input type="radio" name="role" value="student" @checked(old('role') === 'student')>
                    <span>I am a student</span>
                </label>
            </div>
            @error('role') <p class="error">{{ $message }}</p> @enderror

            <div class="field">
                <label for="name">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Your proper name">
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="name@school.ma">
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="At least 8 characters">
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <button class="btn" type="submit">Create account</button>
            <p class="hint">We will send a confirmation email to activate your account.</p>
            <p class="hint">Already registered? <a href="{{ route('login') }}">Sign in</a></p>
        </form>
    </main>
</div>
@endsection
@push('scripts')
<script>
    const form = document.getElementById('register-form');

    // When the person switches between teacher and student...
    form.querySelectorAll('input[name="role"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            // ...empty the name, email and password fields
            form.querySelectorAll('.field input').forEach((input) => {
                input.value = '';
            });
            // ...and remove the red error messages
            form.querySelectorAll('.error').forEach((message) => message.remove());
        });
    });
</script>
@endpush