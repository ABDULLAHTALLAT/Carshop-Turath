<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') | Turath Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="admin-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}">Turath<span>Admin</span></a>
        <nav aria-label="Admin">
            <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>Overview</a>
            <a href="{{ route('admin.cars.index') }}" @if(request()->routeIs('admin.cars.*')) aria-current="page" @endif>Cars</a>
            <a href="{{ route('admin.inquiries.index') }}" @if(request()->routeIs('admin.inquiries.*')) aria-current="page" @endif>Messages</a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">View website</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="push-bottom">
            @csrf
            <button class="link" type="submit">Sign out</button>
        </form>
    </aside>

    <main class="admin-main" id="main">
        @if(session('status'))
            <div class="alert success" role="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</div>
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
