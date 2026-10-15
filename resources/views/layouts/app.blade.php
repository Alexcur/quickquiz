<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Quickquiz')</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">Quickquiz</div>

            <nav class="nav">
                @include(auth()->user()->isTeacher() ? 'partials.nav-teacher' : 'partials.nav-student')
            </nav>

            <div class="sidebar-user">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ ucfirst(auth()->user()->role) }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="link-button">Log out</button>
                </form>
            </div>
        </aside>

        <main class="content">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>