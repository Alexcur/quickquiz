@extends('layouts.app')

@section('title', 'Quizzes - Quickquiz')

@section('content')
<div class="page-header">
    <div>
        <h1>Available quizzes</h1>
        <p class="hint">Open now for you</p>
    </div>
</div>

<div class="join-box">
    <p class="hint">Did not open the link? Type the quiz number from your invitation email.</p>
    <form class="search" role="search">
        <input type="search" name="reference" placeholder="Quiz number, e.g. K7QM-A4XP" aria-label="Quiz number">
        <button class="btn btn-small" type="submit">Find quiz</button>
    </form>
</div>

<div class="empty">
    <p>No quiz is open for you right now.</p>
</div>
@endsection