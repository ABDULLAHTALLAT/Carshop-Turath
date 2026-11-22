<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Dashboard')) | {{ __('Turath Cars') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
@php
    $unread = \App\Models\Inquiry::whereNull('read_at')->whereIn('car_id', auth()->user()->cars()->select('id'))->count();
    $nav = [
        ['owner.dashboard', 'Overview', 'dashboard', request()->routeIs('owner.dashboard'), null],
        ['owner.cars.index', 'My cars', 'car', request()->routeIs('owner.cars.*'), null],
        ['owner.inquiries.index', 'Messages', 'inbox', request()->routeIs('owner.inquiries.*'), $unread],
    ];
    $other = app()->getLocale() === 'ar' ? 'en' : 'ar';
@endphp
<body class="bg-slate-50 text-slate-800 antialiased">
<a href="#main" class="skip-link">{{ __('Skip to content') }}</a>

<div data-drawer-overlay class="pointer-events-none fixed inset-0 z-40 bg-navy/60 opacity-0 backdrop-blur-sm transition-opacity lg:hidden"></div>

<aside data-drawer data-from-start aria-label="{{ __('Owner') }}" class="invisible fixed inset-y-0 start-0 z-50 flex w-72 -translate-x-full flex-col bg-navy p-5 text-white transition-transform duration-300 rtl:translate-x-full lg:!visible lg:!translate-x-0">
    <div class="flex items-center justify-between">
        <a href="{{ route('owner.dashboard') }}"><x-logo :dark="true" /></a>
        <button type="button" data-drawer-close class="grid h-9 w-9 place-items-center rounded-full border border-white/20 lg:hidden" aria-label="{{ __('Close menu') }}"><x-icon name="x" class="h-4 w-4" /></button>
    </div>

    <p class="mb-2 mt-8 px-3 text-[11px] font-bold uppercase tracking-wider text-white/40">{{ __('Manage') }}</p>
    <nav class="admin-nav grid gap-1">
        @foreach($nav as [$route, $label, $icon, $active, $badge])
            <a href="{{ route($route) }}" @if($active) aria-current="page" @endif>
                <x-icon :name="$icon" class="h-5 w-5" /> <span class="flex-1">{{ __($label) }}</span>
                @if($badge)<span class="num rounded-full bg-brand px-2 py-0.5 text-[11px] font-extrabold text-ink">{{ $badge }}</span>@endif
            </a>
        @endforeach
        <a href="{{ route('owner.cars.create') }}"><x-icon name="plus" class="h-5 w-5" /> {{ __('Add car') }}</a>
    </nav>

    <p class="mb-2 mt-8 px-3 text-[11px] font-bold uppercase tracking-wider text-white/40">{{ __('Website') }}</p>
    <nav class="admin-nav grid gap-1">
        <a href="{{ route('home') }}" target="_blank" rel="noopener"><x-icon name="external" class="h-5 w-5" /> {{ __('View website') }}</a>
        <a href="{{ route('lang', $other) }}"><x-icon name="globe" class="h-5 w-5" /> {{ $other === 'ar' ? 'العربية' : 'English' }}</a>
    </nav>

    <div class="mt-auto rounded-2xl bg-white/5 p-4">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-full brand-gradient font-extrabold text-ink">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            <div class="min-w-0"><p class="truncate text-sm font-bold">{{ auth()->user()->name }}</p><p class="truncate text-[11px] text-white/50" dir="ltr">{{ auth()->user()->email }}</p></div>
        </div>
        <form method="POST" action="{{ route('owner.logout') }}" class="mt-3">@csrf
            <button type="submit" class="btn w-full border border-white/15 !text-white hover:bg-white/10 !min-h-[40px] text-[13px]"><x-icon name="log-out" class="h-4 w-4 rtl:rotate-180" /> {{ __('Sign out') }}</button>
        </form>
    </div>
</aside>

<div class="lg:ps-72">
    <header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-3 border-b border-slate-200 bg-white/85 px-4 backdrop-blur-xl sm:px-8">
        <div class="flex items-center gap-3">
            <button type="button" data-menu-toggle class="grid h-10 w-10 place-items-center rounded-full border border-slate-200 lg:hidden" aria-label="{{ __('Open menu') }}"><x-icon name="menu" class="h-5 w-5" /></button>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('owner.inquiries.index') }}" class="relative grid h-10 w-10 place-items-center rounded-full border border-slate-200 text-slate-600 hover:text-brand-dark" aria-label="{{ __('Messages') }}"><x-icon name="bell" class="h-5 w-5" />@if($unread)<span class="num absolute -end-1 -top-1 grid h-5 min-w-[20px] place-items-center rounded-full bg-rose-500 px-1 text-[10px] font-extrabold text-white">{{ $unread }}</span>@endif</a>
            <a href="{{ route('owner.cars.create') }}" class="btn btn-brand btn-sm"><x-icon name="plus" class="h-4 w-4" :stroke="2.4" /> <span class="hidden sm:inline">{{ __('Add car') }}</span></a>
        </div>
    </header>

    <main id="main" class="px-4 py-8 sm:px-8">
        @if(session('status'))
            <div data-flash role="status" class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800"><x-icon name="check-circle" class="h-5 w-5 shrink-0" /> {{ __(session('status')) }}</div>
        @endif
        @yield('content')
    </main>
</div>
<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
