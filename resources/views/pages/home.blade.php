@extends('layouts.app')

@section('full_title', __('Used cars for sale in Egypt').' | '.__('Turath Cars'))
@section('description', __('Search used cars in Egypt by type, price, governorate, traffic unit, colour and licence age. Real papers, honest broker.'))

@php
    $brandList = \App\Models\Car::visible()->select('brand')->distinct()->orderBy('brand')->pluck('brand');
    $bodyTiles = [
        ['Sedan', '#cfd8e3'], ['SUV', '#6bbce9'], ['Hatchback', '#f3f3ef'], ['Pickup', '#b8934a'], ['Van', '#b9bec7'], ['Coupe', '#c0262d'],
    ];
    $steps = [
        ['search', 'Search your way', 'Filter by type, price, governorate, traffic unit, colour and licence age.'],
        ['file-text', 'Check the papers', 'Every car shows its licence, ownership and customs details upfront.'],
        ['handshake', 'Meet the car', 'We arrange a viewing and let you inspect it at any garage you trust.'],
        ['key', 'Drive away', 'We help both sides finish the paperwork at the traffic unit.'],
    ];
@endphp

@section('content')
{{-- ============================== HERO ============================== --}}
<section class="hero-bg relative overflow-hidden text-white">
    <div class="pointer-events-none absolute -top-24 end-[-8%] h-[420px] w-[420px] rounded-full bg-brand/20 blur-3xl float-slow" aria-hidden="true"></div>
    <div class="pointer-events-none absolute bottom-0 start-[-6%] h-[320px] w-[320px] rounded-full bg-sky-500/10 blur-3xl float" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 pb-44 pt-14 lg:grid-cols-[1.05fr_.95fr] lg:pt-20">
        <div>
            <span class="hero-rise glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold text-brand-light">
                <span class="pulse-ring h-2 w-2 rounded-full bg-brand"></span> {{ __('Used cars across Egypt') }}
            </span>
            <h1 class="hero-title mt-6 text-4xl font-extrabold sm:text-5xl lg:text-[3.4rem]">
                <span class="ln"><span class="d-2">{{ __('Find the car') }}</span></span>
                <span class="ln"><span class="d-4 brand-shine">{{ __('that fits your road.') }}</span></span>
            </h1>
            <p class="hero-rise d-6 mt-5 max-w-xl text-[15px] leading-8 text-white/75">{{ __('Search by type, price, place, traffic unit, colour and licence age. Every listing shows the real papers and how the car runs.') }}</p>
            <div class="hero-rise d-8 mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('shop') }}" class="btn btn-brand"><x-icon name="car" class="h-5 w-5" /> {{ __('Browse cars') }} <x-icon name="arrow-right" class="h-4 w-4 flip-rtl" /></a>
                <a href="{{ route('contact') }}" class="btn glass text-white hover:bg-white/15"><x-icon name="plus" class="h-5 w-5" /> {{ __('Sell your car') }}</a>
            </div>
            <ul class="hero-rise d-10 mt-9 grid max-w-xl grid-cols-3 gap-4 text-[12px] font-semibold text-white/80">
                <li class="flex items-center gap-2"><x-icon name="shield-check" class="h-5 w-5 text-brand" /> {{ __('Licence checked') }}</li>
                <li class="flex items-center gap-2"><x-icon name="file-text" class="h-5 w-5 text-brand" /> {{ __('Papers shown') }}</li>
                <li class="flex items-center gap-2"><x-icon name="handshake" class="h-5 w-5 text-brand" /> {{ __('Honest broker') }}</li>
            </ul>
        </div>

        {{-- Real driving clips: each slide enters from a different side --}}
        @php
            $clips = [
                ['drive-1', 'left', 'Toyota Camry'],
                ['drive-2', 'top', 'BMW X7'],
                ['drive-3', 'right', 'BMW M4'],
            ];
        @endphp
        <div class="relative mx-auto w-full max-w-[26rem]" data-hv role="region" aria-roledescription="carousel" aria-label="{{ __('Car videos') }}">
            <div class="hv-frame relative aspect-[9/13] overflow-hidden rounded-[2rem] bg-navy shadow-2xl shadow-black/40 ring-1 ring-white/20">
                @foreach($clips as $i => [$file, $fx, $label])
                    <div class="hv-slide in-{{ $fx }} {{ $i === 0 ? 'is-active' : '' }}" data-hv-slide aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
                        <video class="h-full w-full object-cover" muted playsinline loop preload="{{ $i === 0 ? 'auto' : 'metadata' }}" poster="{{ asset('media/'.$file.'.jpg') }}" aria-label="{{ $label }}">
                            <source src="{{ asset('media/'.$file.'.mp4') }}" type="video/mp4">
                        </video>
                        <span class="hv-caption glass absolute start-4 top-4 inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-[11px] font-bold text-white"><x-icon name="car" class="h-3.5 w-3.5" /> {{ $label }}</span>
                    </div>
                @endforeach
                <div class="pointer-events-none absolute inset-x-0 bottom-0 z-10 h-28 bg-gradient-to-t from-navy/80 to-transparent"></div>
                <div class="absolute inset-x-4 bottom-4 z-10 flex items-center justify-between">
                    <button type="button" data-hv-prev class="pg-arrow hv-btn" aria-label="{{ __('Previous video') }}"><x-icon name="arrow-left" class="h-5 w-5 flip-rtl" /></button>
                    <div class="flex items-center gap-2" role="tablist">
                        @foreach($clips as $i => $c)<button type="button" data-hv-dot role="tab" class="hv-dot {{ $i === 0 ? 'is-on' : '' }}" aria-label="{{ __('Video') }} {{ $i + 1 }}"><i></i></button>@endforeach
                    </div>
                    <button type="button" data-hv-next class="pg-arrow hv-btn" aria-label="{{ __('Next video') }}"><x-icon name="arrow-right" class="h-5 w-5 flip-rtl" /></button>
                </div>
            </div>
            <div class="glass float absolute -end-3 top-10 hidden items-center gap-3 rounded-2xl px-4 py-3 sm:flex">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-brand text-ink"><x-icon name="trending-up" class="h-5 w-5" /></span>
                <span class="text-[11px] leading-tight text-white/70">{{ __('Cars listed') }}<b class="num block text-lg text-white">{{ number_format($stats['cars']) }}+</b></span>
            </div>
            <div class="glass float d-4 absolute -start-3 bottom-24 hidden items-center gap-3 rounded-2xl px-4 py-3 sm:flex">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-emerald-400 text-emerald-950"><x-icon name="id-card" class="h-5 w-5" /></span>
                <span class="text-[11px] leading-tight text-white/70">{{ __('Licence') }}<b class="block text-sm text-white">{{ __('Verified') }}</b></span>
            </div>
        </div>
    </div>

    <a href="#search" class="scroll-cue absolute bottom-24 start-1/2 hidden -translate-x-1/2 text-white/60 lg:block" aria-label="{{ __('Scroll down') }}"><i class="block"><x-icon name="chevron-down" class="h-6 w-6" /></i></a>
    <svg class="absolute inset-x-0 bottom-0 h-16 w-full text-white" viewBox="0 0 1200 64" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0 64V30c200 30 420 36 640 20S1050 0 1200 22v42Z"/></svg>
</section>

{{-- ============================== SEARCH ============================== --}}
<section id="search" class="relative z-10 -mt-32 px-4">
    @include('partials.search-box', ['brands' => $brandList, 'total' => $stats['cars']])
</section>

{{-- ============================== STATS ============================== --}}
<section class="mx-auto mt-14 max-w-7xl px-4">
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach([['car', $stats['cars'], 'Cars available'], ['tag', $stats['brands'], 'Brands'], ['map', $stats['governorates'], 'Governorates covered'], ['check-circle', $stats['sold'], 'Cars sold']] as $i => [$icon, $n, $label])
            <div class="reveal card flex items-center gap-4 p-5 d-{{ $i + 1 }}">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-brand-50 text-brand-dark"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <div><b class="num block text-2xl font-extrabold text-ink" data-count="{{ $n }}">0</b><span class="text-xs text-slate-500">{{ __($label) }}</span></div>
            </div>
        @endforeach
    </div>
</section>

{{-- ============================== BODY TYPES ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="reveal mb-10 text-center">
        <span class="eyebrow">{{ __('Browse by type') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('What are you looking for?') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        @foreach($bodyTiles as $i => [$type, $hex])
            <a href="{{ route('shop', ['body_type' => $type]) }}" class="reveal card group flex flex-col items-center p-4 text-center d-{{ $i + 1 }}">
                <div class="car-stage grid w-full place-items-center rounded-xl px-3 pb-2 pt-4"><x-car-art :type="$type" :color="$hex" :shadow="false" class="car-art w-full" /></div>
                <b class="mt-3 text-sm font-extrabold text-ink">{{ __($type) }}</b>
                <span class="num text-[11px] text-slate-500">{{ $byBody[$type] ?? 0 }} {{ __('cars') }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- ============================== FEATURED ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="reveal mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <span class="eyebrow">{{ __('Fresh listings') }}</span>
            <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('Latest cars') }}</h2>
            <span class="bar mt-4"></span>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" data-snap-prev="featuredRow" class="pg-arrow" aria-label="{{ __('Scroll back') }}"><x-icon name="arrow-left" class="h-5 w-5 flip-rtl" /></button>
            <button type="button" data-snap-next="featuredRow" class="pg-arrow" aria-label="{{ __('Scroll forward') }}"><x-icon name="arrow-right" class="h-5 w-5 flip-rtl" /></button>
        </div>
    </div>
    <div id="featuredRow" class="snap-row">
        @foreach($featured as $car)@include('partials.car-card', ['car' => $car])@endforeach
    </div>
    <div class="mt-8 text-center"><a href="{{ route('shop') }}" class="btn btn-ghost">{{ __('View all cars') }} <x-icon name="arrow-right" class="h-4 w-4 flip-rtl" /></a></div>
</section>

{{-- ============================== WHY ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="grid items-center gap-10 lg:grid-cols-2">
        <div class="reveal-l">
            <span class="eyebrow">{{ __('Why Turath Cars') }}</span>
            <h2 class="mt-2 text-3xl font-extrabold leading-snug text-ink">{{ __('Built around how Egyptians buy cars') }}</h2>
            <span class="bar mt-4"></span>
            <p class="mt-5 text-[15px] leading-8 text-slate-600">{{ __('We list what matters here: the traffic unit that issued the licence, how old the licence is, customs status and the number of owners.') }}</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach([['id-card', 'Licence details', 'Traffic unit, licence type, years valid and licence age.'], ['file-text', 'Full paperwork', 'Ownership, customs and insurance explained clearly.'], ['lock', 'Private plates', 'Plate numbers stay hidden until you are serious.'], ['wrench', 'Service history', 'Know what was fixed and when before you visit.']] as $i => [$icon, $title, $text])
                <div class="reveal-r card p-5 d-{{ $i + 1 }}">
                    <span class="grid h-11 w-11 place-items-center rounded-xl brand-gradient text-ink"><x-icon :name="$icon" class="h-5 w-5" /></span>
                    <h3 class="mt-4 text-[15px] font-extrabold text-ink">{{ __($title) }}</h3>
                    <p class="mt-1.5 text-[13px] leading-6 text-slate-500">{{ __($text) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================== BRANDS MARQUEE ============================== --}}
<section class="mt-24 overflow-hidden border-y border-slate-100 bg-slate-50 py-8" aria-label="{{ __('Brands') }}">
    <div class="marquee overflow-hidden" dir="ltr">
        <div class="marquee-track flex w-max items-center gap-4">
            @foreach([1, 2] as $pass)
                @foreach($brands as $brand => $n)
                    <a href="{{ route('shop', ['brand' => $brand]) }}" @if($pass === 2) aria-hidden="true" tabindex="-1" @endif class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-ink transition hover:border-brand hover:text-brand-dark">
                        <x-icon name="car" class="h-4 w-4 text-brand-dark" /> {{ t($brand) }} <span class="num rounded-full bg-brand-50 px-2 py-0.5 text-[11px] text-brand-dark">{{ $n }}</span>
                    </a>
                @endforeach
            @endforeach
        </div>
    </div>
</section>

{{-- ============================== GOVERNORATES ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="reveal mb-10 text-center">
        <span class="eyebrow">{{ __('Find cars near you') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('Cars in every governorate') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach($byGov as $gov => $n)
            <a href="{{ route('shop', ['governorate' => $gov]) }}" class="reveal card group flex items-center justify-between gap-3 p-5 d-{{ ($loop->index % 4) + 1 }}">
                <span class="flex items-center gap-3"><span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-dark transition group-hover:bg-brand group-hover:text-ink"><x-icon name="pin" class="h-5 w-5" /></span>
                    <b class="text-sm font-extrabold text-ink">{{ t($gov) }}</b></span>
                <span class="num text-xs font-bold text-slate-500">{{ $n }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- ============================== HOW IT WORKS ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="reveal mb-12 text-center">
        <span class="eyebrow">{{ __('How it works') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('From first look to handover') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="relative grid gap-6 md:grid-cols-4">
        <div class="pointer-events-none absolute inset-x-[12%] top-7 hidden h-0.5 bg-gradient-to-r from-brand to-brand-light md:block grow-x d-4"></div>
        @foreach($steps as $i => [$icon, $title, $text])
            <div class="reveal text-center d-{{ $i + 1 }}">
                <span class="relative mx-auto grid h-14 w-14 place-items-center rounded-full brand-gradient text-ink shadow-lg shadow-brand/30 ring-4 ring-white"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <h3 class="mt-4 text-[15px] font-extrabold text-ink">{{ __($title) }}</h3>
                <p class="mx-auto mt-1.5 max-w-[16rem] text-[13px] leading-6 text-slate-500">{{ __($text) }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ============================== CTA ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="hero-bg reveal relative overflow-hidden rounded-3xl px-6 py-14 text-center text-white sm:px-14">
        <div class="pointer-events-none absolute -end-10 -top-10 h-56 w-56 rounded-full bg-brand/25 blur-3xl float" aria-hidden="true"></div>
        <h2 class="relative text-3xl font-extrabold">{{ __('Want to sell your car?') }}</h2>
        <p class="relative mx-auto mt-3 max-w-xl text-[15px] leading-8 text-white/75">{{ __('Send us the details and a few photos. We prepare the listing and handle serious buyers for you.') }}</p>
        <div class="relative mt-7 flex flex-wrap justify-center gap-3">
            <a href="{{ route('contact') }}" class="btn btn-brand"><x-icon name="send" class="h-4 w-4 flip-rtl" /> {{ __('Contact us') }}</a>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="btn btn-wa"><x-icon name="whatsapp" class="h-5 w-5" /> WhatsApp</a>
        </div>
    </div>
</section>
@endsection
