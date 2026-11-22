<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Turath Cars')) | {{ __('Used cars in Egypt') }}</title>
    <meta name="description" content="@yield('description', __('Browse quality used cars across Egypt. Every listing shows the licence, paperwork and how the car runs.'))">
    <meta name="theme-color" content="#0b2447">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/theme.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-white text-slate-800 antialiased">
<a href="#main" class="skip-link">{{ __('Skip to content') }}</a>

@include('partials.header')

<main id="main">
    @if(session('status'))
        <div class="container mx-auto max-w-6xl px-4 pt-6">
            <div data-flash role="status" class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                <x-icon name="check-circle" class="h-5 w-5 shrink-0" /> {{ __(session('status')) }}
            </div>
        </div>
    @endif
    @yield('content')
</main>

@include('partials.footer')
@include('partials.dock')

<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
