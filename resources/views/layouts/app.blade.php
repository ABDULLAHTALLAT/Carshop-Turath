<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Turath Cars') | Used Cars in Egypt</title>
    <meta name="description" content="@yield('description', 'Browse quality used cars across Egypt. Every listing includes full details, paperwork and how the car runs.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header">
        <div class="container nav">
            <a class="brand" href="{{ route('home') }}">Turath<span>Cars</span></a>
            <button class="nav-toggle" type="button" data-nav-toggle aria-label="Toggle menu" aria-expanded="false">&#9776;</button>
            <ul class="nav-links" data-nav-links>
                <li><a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a></li>
                <li><a href="{{ route('shop') }}" @if(request()->routeIs('shop*')) aria-current="page" @endif>Shop</a></li>
                <li><a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>About</a></li>
                <li><a href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact</a></li>
                @auth
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @endif
                @endauth
            </ul>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a class="brand" href="{{ route('home') }}">Turath<span>Cars</span></a>
                    <p class="muted">An independent broker connecting car owners and buyers across Egypt.</p>
                </div>
                <div>
                    <h3>Explore</h3>
                    <ul>
                        <li><a href="{{ route('shop') }}">Shop cars</a></li>
                        <li><a href="{{ route('about') }}">About us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Get in touch</h3>
                    <ul>
                        <li>{{ config('turath.broker.phone') }}</li>
                        <li>{{ config('turath.broker.email') }}</li>
                        <li>{{ config('turath.broker.hours') }}</li>
                    </ul>
                </div>
            </div>
            <p class="copyright">&copy; {{ date('Y') }} {{ config('turath.broker.name') }}. We act as an independent broker only; we do not own the listed cars.</p>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
