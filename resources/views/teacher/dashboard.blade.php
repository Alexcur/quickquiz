@extends('layouts.guest')

@section('title', 'Teacher dashboard - Quickquiz')

@section('content')
<div class="auth-form" style="min-height: 100vh;">
    <div>
        <h2>Teacher dashboard</h2>
        <p class="hint">Hello, {{ auth()->user()->name }}. Your quizzes will appear here.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" type="submit">Log out</button>
        </form>
    </div>
</div>
@endsection