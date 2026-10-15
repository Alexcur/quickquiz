@extends('layouts.app')

@section('title', 'My quizzes - Quickquiz')

@section('content')
<div class="page-header">
    <h1>My quizzes</h1>
    <a href="#" class="btn btn-small">+ New quiz</a>
</div>

<form class="search" role="search">
    <input type="search" name="q" placeholder="Search by title or quiz number" aria-label="Search quizzes">
    <button class="btn btn-small" type="submit">Search</button>
</form>

<div class="empty">
    <p>You have no quizzes yet.</p>
    <p class="hint">The quizzes you create will appear here.</p>
</div>
@endsection