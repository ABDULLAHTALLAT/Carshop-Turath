<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" data-suggest-labels="{{ json_encode(['brand' => __('Brand'), 'model' => __('Model'), 'place' => __('Governorate'), 'unit' => __('Traffic unit')], JSON_UNESCAPED_UNICODE) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $siteName = __('Turath Cars');
        $pageTitle = trim($__env->yieldContent('full_title')) ?: trim($__env->yieldContent('title', $siteName)).' | '.__('Used cars in Egypt');
        $pageDesc = trim($__env->yieldContent('description')) ?: __('Browse quality used cars across Egypt. Every listing shows the licence, paperwork and how the car runs.');
        $pageImage = trim($__env->yieldContent('og_image')) ?: asset('img/og-cover.jpg');
        $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
        $ogType = trim($__env->yieldContent('og_type')) ?: 'website';
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($pageDesc, 160, '') }}">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large')">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="theme-color" content="#0b2447">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($pageDesc, 200, '') }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:locale" content="{{ is_rtl() ? 'ar_EG' : 'en_US' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($pageDesc, 200, '') }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">

    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            ['@type' => 'Organization', '@id' => url('/').'#org', 'name' => $siteName, 'url' => url('/'), 'logo' => asset('img/icon-512.png'),
             'email' => config('turath.broker.email'), 'telephone' => config('turath.broker.phone'), 'areaServed' => 'EG'],
            ['@type' => 'WebSite', '@id' => url('/').'#site', 'url' => url('/'), 'name' => $siteName, 'inLanguage' => app()->getLocale(), 'publisher' => ['@id' => url('/').'#org'],
             'potentialAction' => ['@type' => 'SearchAction', 'target' => url('/shop').'?q={search_term_string}', 'query-input' => 'required name=search_term_string']],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @stack('jsonld')

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
