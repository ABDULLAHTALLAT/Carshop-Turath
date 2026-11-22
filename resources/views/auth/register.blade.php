<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ __('Sell your car') }} | {{ __('Turath Cars') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="hero-bg relative grid min-h-screen place-items-center overflow-hidden p-4 text-white">
<div class="pointer-events-none absolute -top-20 end-10 h-72 w-72 rounded-full bg-brand/25 blur-3xl float-slow" aria-hidden="true"></div>
<div class="pointer-events-none absolute bottom-0 start-0 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl float" aria-hidden="true"></div>

<main class="relative w-full max-w-md">
    <a href="{{ route('home') }}" class="mb-6 flex justify-center"><x-logo :dark="true" /></a>
    <form method="POST" action="{{ route('register.store') }}" class="glass hero-rise rounded-3xl p-7 shadow-2xl">
        @csrf
        <h1 class="flex items-center gap-2 text-xl font-extrabold"><x-icon name="car" class="h-5 w-5 text-brand" /> {{ __('Sell your car') }}</h1>
        <p class="mt-1 text-[13px] text-white/65">{{ __('Create an owner account, add your car and the broker takes it from there.') }}</p>
        <div class="hp" aria-hidden="true"><label>{{ __('Leave this empty') }} <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="mt-6 space-y-4">
            @foreach([['name', 'Your name', 'text', 'user', 'name'], ['email', 'Email', 'email', 'mail', 'email'], ['phone', 'Phone', 'tel', 'phone', 'tel'], ['password', 'Password', 'password', 'key', 'new-password'], ['password_confirmation', 'Confirm password', 'password', 'key', 'new-password']] as [$n, $label, $type, $icon, $ac])
                <div><label class="mb-1.5 block text-xs font-semibold text-white/80" for="{{ $n }}">{{ __($label) }}</label>
                    <div class="relative"><x-icon :name="$icon" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input id="{{ $n }}" type="{{ $type }}" name="{{ $n }}" value="{{ $type === 'password' ? '' : old($n) }}" autocomplete="{{ $ac }}" required @if($type !== 'text') dir="ltr" @endif class="field ps-10 !bg-white !text-slate-900"></div>
                    @error($n)<p class="mt-1.5 text-xs font-semibold text-rose-300" role="alert">{{ $message }}</p>@enderror</div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-brand mt-6 w-full"><x-icon name="check-circle" class="h-5 w-5" /> {{ __('Create account') }}</button>
        <p class="mt-4 text-center text-[12px] text-white/60">{{ __('Already have an account?') }} <a class="font-bold text-brand" href="{{ route('login') }}">{{ __('Sign in') }}</a></p>
    </form>
</main>
</body>
</html>
