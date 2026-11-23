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
@php $clips = ['drive-1' => 'Toyota Camry', 'drive-2' => 'BMW X7', 'drive-3' => 'BMW M4']; @endphp
<section class="hero-bg relative isolate overflow-hidden text-white">
    {{-- Full-width background clips, played one after another --}}
    <div class="hv-bg absolute inset-0 -z-10" data-hv aria-hidden="true">
        @foreach($clips as $file => $label)
            <video class="hv-video {{ $loop->first ? 'is-active' : '' }}" muted playsinline preload="{{ $loop->first ? 'auto' : 'metadata' }}" poster="{{ asset('media/'.$file.'.jpg') }}" data-hv-video>
                <source src="{{ asset('media/'.$file.'.mp4') }}" type="video/mp4">
            </video>
        @endforeach
        <div class="absolute inset-0 bg-gradient-to-r from-navy/90 via-navy/60 to-navy/20 rtl:bg-gradient-to-l"></div>
        <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-navy/80 to-transparent"></div>
    </div>

    <div class="relative mx-auto flex min-h-[640px] max-w-7xl items-center px-4 pb-44 pt-16 lg:min-h-[720px]">
        <div class="max-w-2xl">
            <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold text-brand-light">
                <span class="h-2 w-2 rounded-full bg-brand"></span> {{ __('Used cars across Egypt') }}
            </span>
            <h1 class="hero-title mt-6 text-4xl font-extrabold sm:text-5xl lg:text-[3.6rem]">
                <span class="ln"><span >{{ __('Find the car') }}</span></span>
                <span class="ln"><span class="brand-shine">{{ __('that fits your road.') }}</span></span>
            </h1>
            <p class="mt-5 max-w-xl text-[15px] leading-8 text-white/80">{{ __('Search by type, price, place, traffic unit, colour and licence age. Every listing shows the real papers and how the car runs.') }}</p>
            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('shop') }}" class="btn btn-brand"><x-icon name="car" class="h-5 w-5" /> {{ __('Browse cars') }} <x-icon name="arrow-right" class="h-4 w-4 flip-rtl" /></a>
                <a href="{{ route('register') }}" class="btn glass text-white hover:bg-white/15"><x-icon name="plus" class="h-5 w-5" /> {{ __('Sell your car') }}</a>
            </div>
            <ul class="mt-9 grid max-w-xl grid-cols-3 gap-4 text-[12px] font-semibold text-white/85">
                <li class="flex items-center gap-2"><x-icon name="shield-check" class="h-5 w-5 text-brand" /> {{ __('Licence checked') }}</li>
                <li class="flex items-center gap-2"><x-icon name="file-text" class="h-5 w-5 text-brand" /> {{ __('Papers shown') }}</li>
                <li class="flex items-center gap-2"><x-icon name="handshake" class="h-5 w-5 text-brand" /> {{ __('Honest broker') }}</li>
            </ul>
        </div>
    </div>

    <a href="#search" class="absolute bottom-24 start-1/2 hidden -translate-x-1/2 text-white/70 lg:block" aria-label="{{ __('Scroll down') }}"><i class="block"><x-icon name="chevron-down" class="h-6 w-6" /></i></a>
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
            <div class="card flex items-center gap-4 p-5">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-brand-50 text-brand-dark"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <div><b class="num block text-2xl font-extrabold text-ink" data-count="{{ $n }}">0</b><span class="text-xs text-slate-500">{{ __($label) }}</span></div>
            </div>
        @endforeach
    </div>
</section>

{{-- ============================== BODY TYPES ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="mb-10 text-center">
        <span class="eyebrow">{{ __('Browse by type') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('What are you looking for?') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        @foreach($bodyTiles as $i => [$type, $hex])
            <a href="{{ route('shop', ['body_type' => $type]) }}" class="card group flex flex-col items-center p-4 text-center">
                <div class="car-stage grid w-full place-items-center rounded-xl px-3 pb-2 pt-4"><x-car-art :type="$type" :color="$hex" :shadow="false" class="car-art w-full" /></div>
                <b class="mt-3 text-sm font-extrabold text-ink">{{ __($type) }}</b>
                <span class="num text-[11px] text-slate-500">{{ $byBody[$type] ?? 0 }} {{ __('cars') }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- ============================== FEATURED ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
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
        <div >
            <span class="eyebrow">{{ __('Why Turath Cars') }}</span>
            <h2 class="mt-2 text-3xl font-extrabold leading-snug text-ink">{{ __('Built around how Egyptians buy cars') }}</h2>
            <span class="bar mt-4"></span>
            <p class="mt-5 text-[15px] leading-8 text-slate-600">{{ __('We list what matters here: the traffic unit that issued the licence, how old the licence is, customs status and the number of owners.') }}</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach([['id-card', 'Licence details', 'Traffic unit, licence type, years valid and licence age.'], ['file-text', 'Full paperwork', 'Ownership, customs and insurance explained clearly.'], ['lock', 'Private plates', 'Plate numbers stay hidden until you are serious.'], ['wrench', 'Service history', 'Know what was fixed and when before you visit.']] as $i => [$icon, $title, $text])
                <div class="card p-5">
                    <span class="grid h-11 w-11 place-items-center rounded-xl brand-gradient text-ink"><x-icon :name="$icon" class="h-5 w-5" /></span>
                    <h3 class="mt-4 text-[15px] font-extrabold text-ink">{{ __($title) }}</h3>
                    <p class="mt-1.5 text-[13px] leading-6 text-slate-500">{{ __($text) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================== BRANDS (real logo images) ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4" aria-label="{{ __('Brands') }}">
    <div class="mb-10 text-center">
        <span class="eyebrow">{{ __('Browse by brand') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('Popular brands') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-7">
        @foreach($brands as $brand => $n)
            @php $logo = 'img/brands/'.\Illuminate\Support\Str::slug($brand).'.png'; @endphp
            <a href="{{ route('shop', ['brand' => $brand]) }}" class="card flex flex-col items-center gap-3 p-5 text-center">
                @if(is_file(public_path($logo)))
                    <img src="{{ asset($logo) }}" alt="{{ t($brand) }}" loading="lazy" width="96" height="64" class="h-16 w-24 object-contain">
                @else
                    <span class="grid h-16 w-24 place-items-center rounded-xl bg-brand-50 text-lg font-extrabold text-brand-dark">{{ mb_substr($brand, 0, 2) }}</span>
                @endif
                <span><b class="block text-[13px] font-extrabold text-ink">{{ t($brand) }}</b><span class="num text-[11px] text-slate-500">{{ $n }} {{ __('cars') }}</span></span>
            </a>
        @endforeach
    </div>
</section>

{{-- ============================== GOVERNORATES ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="mb-10 text-center">
        <span class="eyebrow">{{ __('Find cars near you') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('Cars in every governorate') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach($byGov as $gov => $n)
            @php $slugG = \Illuminate\Support\Str::slug($gov); $placeFile = public_path('img/places/'.$slugG.'.jpg'); @endphp
            <a href="{{ route('shop', ['governorate' => $gov]) }}" class="group relative isolate flex aspect-[4/3] items-end overflow-hidden rounded-2xl bg-navy">
                @if(is_file($placeFile))
                    <img src="{{ asset('img/places/'.$slugG.'.jpg') }}" alt="{{ t($gov) }}" loading="lazy" class="absolute inset-0 -z-10 h-full w-full object-cover">
                @else
                    <span class="hero-bg absolute inset-0 -z-10" aria-hidden="true"></span>
                    <x-icon name="map" class="absolute end-4 top-4 -z-10 h-12 w-12 text-white/15" />
                @endif
                <span class="absolute inset-0 -z-10 bg-gradient-to-t from-navy/85 via-navy/20 to-transparent"></span>
                <span class="flex w-full items-center justify-between p-4 text-white"><b class="text-sm font-extrabold">{{ t($gov) }}</b><span class="num rounded-full bg-white/20 px-2.5 py-0.5 text-[11px] font-bold backdrop-blur">{{ $n }} {{ __('cars') }}</span></span>
            </a>
        @endforeach
    </div>
</section>

{{-- ============================== HOW IT WORKS ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="mb-12 text-center">
        <span class="eyebrow">{{ __('How it works') }}</span>
        <h2 class="mt-2 text-3xl font-extrabold text-ink">{{ __('From first look to handover') }}</h2>
        <span class="bar mx-auto mt-4"></span>
    </div>
    <div class="relative grid gap-6 md:grid-cols-4">
        <div class="pointer-events-none absolute inset-x-[12%] top-7 hidden h-0.5 bg-gradient-to-r from-brand to-brand-light md:block grow-x"></div>
        @foreach($steps as $i => [$icon, $title, $text])
            <div class="text-center">
                <span class="relative mx-auto grid h-14 w-14 place-items-center rounded-full brand-gradient text-ink shadow-lg shadow-brand/30 ring-4 ring-white"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <h3 class="mt-4 text-[15px] font-extrabold text-ink">{{ __($title) }}</h3>
                <p class="mx-auto mt-1.5 max-w-[16rem] text-[13px] leading-6 text-slate-500">{{ __($text) }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ============================== CTA ============================== --}}
<section class="mx-auto mt-24 max-w-7xl px-4">
    <div class="hero-bg relative overflow-hidden rounded-3xl px-6 py-14 text-center text-white sm:px-14">
        <div class="pointer-events-none absolute -end-10 -top-10 h-56 w-56 rounded-full bg-brand/25 blur-3xl" aria-hidden="true"></div>
        <h2 class="relative text-3xl font-extrabold">{{ __('Want to sell your car?') }}</h2>
        <p class="relative mx-auto mt-3 max-w-xl text-[15px] leading-8 text-white/75">{{ __('Send us the details and a few photos. We prepare the listing and handle serious buyers for you.') }}</p>
        <div class="relative mt-7 flex flex-wrap justify-center gap-3">
            <a href="{{ route('contact') }}" class="btn btn-brand"><x-icon name="send" class="h-4 w-4 flip-rtl" /> {{ __('Contact us') }}</a>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="btn btn-wa"><x-icon name="whatsapp" class="h-5 w-5" /> WhatsApp</a>
        </div>
    </div>
</section>
@endsection
