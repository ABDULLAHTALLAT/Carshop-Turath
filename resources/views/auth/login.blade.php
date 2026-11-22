<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ __('Sign in') }} | {{ __('Turath Cars') }}</title>
    <script src="{{ asset('js/theme.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hero-bg relative grid min-h-screen place-items-center overflow-hidden p-4 text-white">
<div class="pointer-events-none absolute -top-20 end-10 h-72 w-72 rounded-full bg-brand/25 blur-3xl float-slow" aria-hidden="true"></div>
<div class="pointer-events-none absolute bottom-0 start-0 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl float" aria-hidden="true"></div>

<main class="relative w-full max-w-md">
    <a href="{{ route('home') }}" class="mb-6 flex justify-center"><x-logo :dark="true" /></a>
    <form method="POST" action="{{ route('login.store') }}" class="glass hero-rise rounded-3xl p-7 shadow-2xl">
        @csrf
        <h1 class="flex items-center gap-2 text-xl font-extrabold"><x-icon name="lock" class="h-5 w-5 text-brand" /> {{ __('Admin sign in') }}</h1>
        <p class="mt-1 text-[13px] text-white/65">{{ __('Only the broker can open the dashboard.') }}</p>

        <div class="mt-6 space-y-4">
            <div><label class="mb-1.5 block text-xs font-semibold text-white/80" for="email">{{ __('Email') }}</label>
                <div class="relative"><x-icon name="mail" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus dir="ltr" class="field ps-10 !bg-white !text-slate-900"></div>
                @error('email')<p class="mt-1.5 text-xs font-semibold text-rose-300" role="alert">{{ $message }}</p>@enderror</div>
            <div><label class="mb-1.5 block text-xs font-semibold text-white/80" for="password">{{ __('Password') }}</label>
                <div class="relative"><x-icon name="key" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input id="password" type="password" name="password" autocomplete="current-password" required dir="ltr" class="field ps-10 !bg-white !text-slate-900"></div></div>
        </div>
        <button type="submit" class="btn btn-brand mt-6 w-full"><x-icon name="log-out" class="h-5 w-5 rtl:rotate-180" /> {{ __('Sign in') }}</button>
        <p class="mt-4 flex items-center justify-center gap-1.5 text-[11px] text-white/50"><x-icon name="shield-check" class="h-3.5 w-3.5" /> {{ __('Too many attempts are blocked for a short time.') }}</p>
    </form>
</main>
</body>
</html>
