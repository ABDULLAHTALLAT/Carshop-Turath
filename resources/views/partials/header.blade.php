@php
    $links = [
        ['home', 'Home', 'home', request()->routeIs('home')],
        ['shop', 'Shop', 'car', request()->routeIs('shop*')],
        ['about', 'About', 'users', request()->routeIs('about')],
        ['contact', 'Contact', 'mail', request()->routeIs('contact')],
    ];
    $other = app()->getLocale() === 'ar' ? 'en' : 'ar';
@endphp
<header class="relative z-40">
    {{-- Top bar --}}
    <div class="bg-navy text-[12px] text-white/80">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-2">
            <div class="flex items-center gap-5">
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', config('turath.broker.phone')) }}" class="inline-flex items-center gap-1.5 hover:text-brand">
                    <x-icon name="phone" class="h-3.5 w-3.5" /> <span dir="ltr" class="num">{{ config('turath.broker.phone') }}</span>
                </a>
                <a href="mailto:{{ config('turath.broker.email') }}" class="hidden items-center gap-1.5 hover:text-brand sm:inline-flex">
                    <x-icon name="mail" class="h-3.5 w-3.5" /> {{ config('turath.broker.email') }}
                </a>
            </div>
            <div class="flex items-center gap-4">
                <span class="hidden items-center gap-1.5 md:inline-flex"><x-icon name="clock" class="h-3.5 w-3.5" /> {{ __('Sat - Thu, 10:00 - 20:00') }}</span>
                <a href="{{ route('lang', $other) }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/20 px-3 py-1 font-bold text-white hover:border-brand hover:text-brand" hreflang="{{ $other }}" lang="{{ $other }}">
                    <x-icon name="globe" class="h-3.5 w-3.5" /> {{ $other === 'ar' ? 'العربية' : 'English' }}
                </a>
            </div>
        </div>
    </div>

    {{-- Main bar --}}
    <div class="sticky top-0 border-b border-slate-100 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-4 px-4">
            <a href="{{ route('home') }}" aria-label="{{ __('Turath Cars') }}"><x-logo /></a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="{{ __('Main') }}">
                @foreach($links as [$route, $label, $icon, $active])
                    <a href="{{ route($route) }}" @if($active) aria-current="page" @endif
                       class="group relative inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold {{ $active ? 'text-ink bg-brand-50' : 'text-slate-600 hover:text-ink hover:bg-slate-50' }}">
                        <x-icon :name="$icon" class="h-4 w-4 {{ $active ? 'text-brand-dark' : 'text-slate-400 group-hover:text-brand-dark' }}" /> {{ __($label) }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('shop') }}" class="grid h-10 w-10 place-items-center rounded-full border border-slate-200 text-slate-600 hover:border-brand hover:text-brand-dark" aria-label="{{ __('Search cars') }}"><x-icon name="search" class="h-5 w-5" /></a>
                @auth
                    <a href="{{ route(auth()->user()->homeRoute()) }}" class="btn btn-primary btn-sm hidden sm:inline-flex"><x-icon name="dashboard" class="h-4 w-4" /> {{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="grid h-10 w-10 place-items-center rounded-full border border-slate-200 text-slate-600 hover:border-brand hover:text-brand-dark" aria-label="{{ __('Admin login') }}"><x-icon name="lock" class="h-5 w-5" /></a>
                @endauth
                <a href="{{ auth()->check() && ! auth()->user()->is_admin ? route('owner.cars.create') : route('register') }}" class="btn btn-brand btn-sm hidden md:inline-flex"><x-icon name="plus" class="h-4 w-4" :stroke="2.4" /> {{ __('Sell your car') }}</a>
                <button type="button" data-menu-toggle class="grid h-10 w-10 place-items-center rounded-full border border-slate-200 text-slate-700 lg:hidden" aria-label="{{ __('Open menu') }}"><x-icon name="menu" class="h-5 w-5" /></button>
            </div>
        </div>
    </div>
</header>

{{-- Mobile drawer --}}
<div data-drawer-overlay class="pointer-events-none fixed inset-0 z-50 bg-navy/60 opacity-0 backdrop-blur-sm lg:hidden"></div>
<aside data-drawer aria-hidden="true" class="invisible fixed inset-y-0 end-0 z-50 w-[84%] max-w-sm translate-x-full bg-white p-5 shadow-2xl rtl:-translate-x-full lg:hidden">
    <div class="mb-6 flex items-center justify-between"><x-logo />
        <button type="button" data-drawer-close class="grid h-10 w-10 place-items-center rounded-full border border-slate-200" aria-label="{{ __('Close menu') }}"><x-icon name="x" class="h-5 w-5" /></button>
    </div>
    <nav class="grid gap-1" aria-label="{{ __('Mobile') }}">
        @foreach($links as [$route, $label, $icon, $active])
            <a href="{{ route($route) }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-semibold {{ $active ? 'bg-brand-50 text-ink' : 'text-slate-600 hover:bg-slate-50' }}">
                <x-icon :name="$icon" class="h-5 w-5 text-brand-dark" /> {{ __($label) }}
            </a>
        @endforeach
        <a href="{{ route('register') }}" class="btn btn-brand mt-4"><x-icon name="plus" class="h-4 w-4" /> {{ __('Sell your car') }}</a>
        @auth
            @if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}" class="btn btn-ghost mt-2"><x-icon name="dashboard" class="h-4 w-4" /> {{ __('Dashboard') }}</a>@endif
        @endauth
    </nav>
</aside>
