@extends('layouts.guest')

@section('title', 'Student dashboard - Quickquiz')

@section('content')
<div class="auth-form" style="min-height: 100vh;">
    <div>
        <h2>Student dashboard</h2>
        <p class="hint">Hello, {{ auth()->user()->name }}. The quizzes you can take will appear here.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" type="submit">Log out</button>
        </form>
    </div>
</div>
@endsection