@extends('layouts.app')

@section('title', __('About us'))
@section('description', __('An independent broker connecting car owners and buyers across Egypt. We never own the cars we list.'))

@php
    $values = [
        ['eye', 'Total clarity', 'We show the licence, the paperwork and how the car runs before anyone drives across town.'],
        ['handshake', 'Honest brokerage', 'You always know you are dealing with an independent broker, never a hidden owner.'],
        ['shield-check', 'Safer deals', 'Plates stay private, papers are checked and every viewing is arranged by us.'],
        ['zap', 'Fast and simple', 'Clear listings, quick answers on WhatsApp and no pressure to decide.'],
    ];
    $timeline = [
        ['file-text', 'Owner sends details', 'The owner shares the car data, papers and photos with us.'],
        ['shield-check', 'We check everything', 'We review the licence, traffic unit, customs papers and ownership chain.'],
        ['car', 'Listing goes live', 'The car appears on the website with full, honest details.'],
        ['handshake', 'Viewing and handover', 'We match serious buyers, arrange viewings and finish the paperwork.'],
    ];
    $faqs = [
        ['Do you own the cars?', 'No. We are an independent broker. The cars belong to their owners and we connect buyers with them.'],
        ['What does the licence information mean?', 'Each listing shows the traffic unit that issued the licence, the licence type, how many years it is valid and how old the licence is.'],
        ['Why are plate numbers hidden?', 'To protect the owner. We share the full plate once a buyer is serious and a viewing is arranged.'],
        ['Can I inspect the car at my own garage?', 'Yes. We encourage it, and we will arrange the time with the owner.'],
        ['How much do you charge?', 'Contact us and we will explain our simple commission. There are no hidden fees.'],
    ];
@endphp

@section('content')
{{-- ============================== HERO ============================== --}}
<section class="hero-bg relative isolate overflow-hidden pb-24 pt-14 text-white">
    <img src="{{ asset('media/hero.jpg') }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover opacity-25" width="1280" height="720" loading="eager" decoding="async">
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-navy/95 via-navy/80 to-navy/40 rtl:bg-gradient-to-l" aria-hidden="true"></div>
    <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 lg:grid-cols-[1.1fr_1fr]">
        <div>
            <nav class="flex items-center gap-2 text-xs text-white/60" aria-label="{{ __('Breadcrumb') }}">
                <a href="{{ route('home') }}" class="hover:text-brand">{{ __('Home') }}</a><x-icon name="chevron-right" class="h-3 w-3 flip-rtl" /><span class="text-white">{{ __('About us') }}</span>
            </nav>
            <span class="glass mt-5 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold text-brand-light"><x-icon name="users" class="h-4 w-4" /> {{ __('About us') }}</span>
            <h1 class="hero-title mt-5 text-4xl font-extrabold sm:text-5xl">
                <span class="ln"><span>{{ __('A simple, honest way') }}</span></span>
                <span class="ln"><span class="brand-shine">{{ __('to buy and sell cars.') }}</span></span>
            </h1>
            <p class="mt-5 max-w-xl text-[15px] leading-8 text-white/75">{{ __('Turath Cars is an independent broker in Egypt. Owners list their cars with every detail, and buyers decide with confidence.') }}</p>
            <ul class="mt-7 flex flex-wrap gap-3 text-[12px] font-semibold">
                <li class="glass inline-flex items-center gap-2 rounded-full px-3.5 py-2"><x-icon name="shield-check" class="h-4 w-4 text-brand" /> {{ __('Licence checked') }}</li>
                <li class="glass inline-flex items-center gap-2 rounded-full px-3.5 py-2"><x-icon name="file-text" class="h-4 w-4 text-brand" /> {{ __('Papers shown') }}</li>
                <li class="glass inline-flex items-center gap-2 rounded-full px-3.5 py-2"><x-icon name="handshake" class="h-4 w-4 text-brand" /> {{ __('Honest broker') }}</li>
            </ul>
        </div>
        {{-- Real car photos, no motion --}}
        <div class="relative mx-auto hidden h-64 w-full max-w-lg sm:block lg:h-80" aria-hidden="true" dir="ltr">
            <img src="{{ asset('img/cars/honda-civic-lx.webp') }}" alt="" class="absolute bottom-0 left-0 w-3/5 drop-shadow-2xl" loading="lazy" decoding="async">
            <img src="{{ asset('img/cars/toyota-camry.webp') }}" alt="" class="absolute bottom-6 right-0 w-3/5 drop-shadow-2xl" loading="lazy" decoding="async">
            <img src="{{ asset('img/cars/mercedes-cla.webp') }}" alt="" class="absolute left-1/4 top-0 w-1/2 drop-shadow-2xl" loading="lazy" decoding="async">
        </div>
    </div>
    <svg class="absolute inset-x-0 bottom-0 h-14 w-full text-white" viewBox="0 0 1200 64" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0 64V34c220 26 440 30 660 14S1040 4 1200 26v38Z"/></svg>
</section>

{{-- ============================== STORY + STATS ============================== --}}
<section class="mx-auto mt-16 max-w-7xl px-4">
    <div class="grid items-center gap-12 lg:grid-cols-2">
        <div >
            <span class="eyebrow">{{ __('Our story') }}</span>
            <h2 class="mt-2 text-3xl font-extrabold leading-snug text-ink">{{ __('Buying a used car should not feel like a gamble') }}</h2>
            <span class="bar mt-4"></span>
            <p class="mt-5 text-[15px] leading-8 text-slate-600">{{ __('We started Turath Cars to remove the guesswork. Every car is listed with its traffic unit, licence age, customs status, owners and service history.') }}</p>
            <p class="mt-3 text-[15px] leading-8 text-slate-600">{{ __('We are brokers only. We never own the cars, so our job is simple: make sure both sides have the facts and a fair deal.') }}</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-6">{{ __('Browse cars') }} <x-icon name="arrow-right" class="h-4 w-4 flip-rtl" /></a>
        </div>
        <div class="relative">
            <div class="car-stage rounded-3xl border border-slate-100 p-8">
                <div ><x-car-art type="SUV" color="#14284b" /></div>
                <div class="mt-2 flex justify-center gap-3">
                    @foreach(['#f3f3ef', '#17191d', '#b9bec7', '#c0262d', '#1f55b3'] as $hex)
                        <span class="h-6 w-6 rounded-full ring-2 ring-slate-200 {{ $loop->first ? 'bg-[#f3f3ef]' : '' }} {{ $hex === '#17191d' ? 'bg-[#17191d]' : '' }} {{ $hex === '#b9bec7' ? 'bg-[#b9bec7]' : '' }} {{ $hex === '#c0262d' ? 'bg-[#c0262d]' : '' }} {{ $hex === '#1f55b3' ? 'bg-[#1f55b3]' : '' }}"></span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-14 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach([['car', $stats['cars'], 'Cars listed'], ['check-circle', $stats['sold'], 'Cars sold'], ['map', $stats['governorates'], 'Governorates'], ['tag', $stats['brands'], 'Brands']] as $i => [$icon, $n, $label])
            <div class="card p-6 text-center">
                <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-brand-50 text-brand-dark"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <b class="num mt-3 block text-3xl font-extrabold text-ink" data-count="{{ $n }}">0</b>
                <span class="text-xs text-slate-500">{{ __($label) }}</span>
            </div>
        @endforeach
    </div>
</section>

{{-- ============================== VALUES ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="mb-10 text-center">
        <span class="eyebrow">{{ __('What we stand for') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('Our values') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($values as $i => [$icon, $title, $text])
            <div class="card p-6">
                <span class="grid h-12 w-12 place-items-center rounded-2xl brand-gradient text-ink"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <h3 class="mt-4 text-[15px] font-extrabold text-ink">{{ __($title) }}</h3>
                <p class="mt-2 text-[13px] leading-7 text-slate-500">{{ __($text) }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ============================== PROCESS ============================== --}}
<section class="mx-auto mt-24 max-w-5xl px-4">
    <div class="mb-12 text-center">
        <span class="eyebrow">{{ __('How we work') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('Four steps, no surprises') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <ol class="relative space-y-8">
        <span class="absolute inset-y-4 start-7 w-0.5 bg-gradient-to-b from-brand to-brand-light/20" aria-hidden="true"></span>
        @foreach($timeline as $i => [$icon, $title, $text])
            <li class="relative flex gap-5">
                <span class="relative z-10 grid h-14 w-14 shrink-0 place-items-center rounded-full brand-gradient text-ink shadow-lg shadow-brand/30 ring-4 ring-white"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <div class="card flex-1 p-5">
                    <span class="num text-xs font-bold text-brand-dark">0{{ $i + 1 }}</span>
                    <h3 class="text-[15px] font-extrabold text-ink">{{ __($title) }}</h3>
                    <p class="mt-1 text-[13px] leading-7 text-slate-500">{{ __($text) }}</p>
                </div>
            </li>
        @endforeach
    </ol>
</section>

{{-- ============================== FAQ ============================== --}}
<section class="mx-auto mt-24 max-w-3xl px-4">
    <div class="mb-10 text-center">
        <span class="eyebrow">{{ __('FAQ') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('Questions people ask') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="space-y-3">
        @foreach($faqs as $i => [$q, $a])
            <details class="card group p-5">
                <summary class="flex items-center justify-between gap-4 text-[14px] font-bold text-ink">
                    <span class="flex items-center gap-3"><x-icon name="help" class="h-5 w-5 text-brand-dark" /> {{ __($q) }}</span>
                    <span class="faq-sign grid h-8 w-8 shrink-0 place-items-center rounded-full bg-brand-50 text-brand-dark"><x-icon name="plus" class="h-4 w-4" /></span>
                </summary>
                <p class="mt-3 ps-8 text-[13px] leading-7 text-slate-500">{{ __($a) }}</p>
            </details>
        @endforeach
    </div>
</section>

<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="hero-bg relative overflow-hidden rounded-3xl px-6 py-12 text-center text-white">
        <h2 class="text-2xl font-extrabold">{{ __('Ready to find or sell a car?') }}</h2>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('shop') }}" class="btn btn-brand"><x-icon name="car" class="h-5 w-5" /> {{ __('Browse cars') }}</a>
            <a href="{{ route('contact') }}" class="btn glass text-white hover:bg-white/15"><x-icon name="mail" class="h-5 w-5" /> {{ __('Contact us') }}</a>
        </div>
    </div>
</section>
@endsection
