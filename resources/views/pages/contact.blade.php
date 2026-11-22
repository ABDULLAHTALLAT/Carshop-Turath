@extends('layouts.app')

@section('title', __('Contact us'))
@section('description', __('Ask about a listing, or tell us about the car you want to sell.'))

@section('content')
<section class="hero-bg relative overflow-hidden pb-24 pt-12 text-white">
    <div class="pointer-events-none absolute -top-16 start-10 h-52 w-52 rounded-full bg-brand/20 blur-3xl float-slow" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-7xl px-4">
        <span class="hero-rise glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold text-brand-light"><x-icon name="mail" class="h-4 w-4" /> {{ __('Contact') }}</span>
        <h1 class="hero-rise d-2 mt-4 text-3xl font-extrabold sm:text-4xl">{{ __('Let us talk about your next car') }}</h1>
        <p class="hero-rise d-3 mt-3 max-w-xl text-sm leading-7 text-white/70">{{ __('Ask about a listing, or tell us about the car you want to sell.') }}</p>
    </div>
    <svg class="absolute inset-x-0 bottom-0 h-10 w-full text-white" viewBox="0 0 1200 40" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0 40V18c240 20 520 22 760 8S1100 4 1200 14v26Z"/></svg>
</section>

<section class="mx-auto -mt-12 grid max-w-7xl gap-8 px-4 pb-6 lg:grid-cols-[1.4fr_1fr]">
    <form class="card relative z-10 p-6 sm:p-8" method="POST" action="{{ route('contact.store') }}" novalidate>
        @csrf
        @if($car)
            <input type="hidden" name="car" value="{{ $car->slug }}">
            <div class="mb-6 flex items-center gap-4 rounded-2xl bg-brand-50 p-4">
                <div class="car-stage w-24 shrink-0 rounded-xl p-2"><x-car-art :type="$car->body_type" :color="$car->color_hex" :shadow="false" class="w-full" /></div>
                <div class="min-w-0"><p class="text-[11px] text-slate-500">{{ __('You are asking about') }}</p><p class="truncate text-sm font-extrabold text-ink">{{ $car->display_title }}</p><p class="num text-xs font-bold text-brand-dark">{{ egp($car->price) }}</p></div>
            </div>
        @endif

        <div class="hp" aria-hidden="true"><label>{{ __('Leave this empty') }} <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div><label class="label" for="name">{{ __('Your name') }}</label>
                <input id="name" class="field" name="name" value="{{ old('name') }}" autocomplete="name" required>
                @error('name')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div><label class="label" for="phone">{{ __('Phone (optional)') }}</label>
                <input id="phone" class="field" name="phone" value="{{ old('phone') }}" autocomplete="tel" inputmode="tel" dir="ltr">
                @error('phone')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="sm:col-span-2"><label class="label" for="email">{{ __('Email') }}</label>
                <input id="email" class="field" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required dir="ltr">
                @error('email')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="sm:col-span-2"><label class="label" for="subject">{{ __('Subject') }}</label>
                <input id="subject" class="field" name="subject" value="{{ old('subject', $car ? __('Question about').' '.$car->display_title : '') }}" required>
                @error('subject')<p class="field-error">{{ $message }}</p>@enderror</div>
            <div class="sm:col-span-2"><label class="label" for="message">{{ __('Message') }}</label>
                <textarea id="message" class="field" name="message" required>{{ old('message') }}</textarea>
                @error('message')<p class="field-error">{{ $message }}</p>@enderror</div>
        </div>
        <button class="btn btn-brand mt-6 w-full sm:w-auto" type="submit"><x-icon name="send" class="h-5 w-5 flip-rtl" /> {{ __('Send message') }}</button>
    </form>

    <aside class="relative z-10 space-y-4">
        @foreach([['phone', 'Phone', config('turath.broker.phone'), true], ['mail', 'Email', config('turath.broker.email'), true], ['clock', 'Working hours', __('Sat - Thu, 10:00 - 20:00'), false], ['pin', 'Location', __('Cairo, Egypt'), false]] as $i => [$icon, $label, $value, $ltr])
            <div class="reveal card flex items-center gap-4 p-5 d-{{ $i + 1 }}">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-brand-50 text-brand-dark"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <div class="min-w-0"><p class="text-[11px] text-slate-500">{{ __($label) }}</p><p class="num truncate text-sm font-extrabold text-ink" @if($ltr) dir="ltr" @endif>{{ $value }}</p></div>
            </div>
        @endforeach
        <a class="btn btn-wa w-full !min-h-[52px]" href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}" target="_blank" rel="noopener noreferrer"><x-icon name="whatsapp" class="h-6 w-6" /> {{ __('Chat on WhatsApp') }}</a>
    </aside>
</section>
@endsection
