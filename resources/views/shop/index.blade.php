@extends('layouts.app')

@section('title', __('Used cars for sale in Egypt'))
@section('description', __('Browse quality used cars across Egypt. Every listing shows the licence, paperwork and how the car runs.'))
@section('canonical', route('shop'))
@if(request()->query())
@section('robots', 'noindex, follow')
@endif

@php
    $units = \App\Models\Car::trafficUnits();
    $sorts = ['newest' => 'Newest', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'year_desc' => 'Year: newest', 'mileage_asc' => 'Mileage: lowest'];
    $active = collect($filters)->except('sort')->filter(fn ($v) => $v !== null && $v !== '');
    $years = range((int) date('Y'), (int) date('Y') - 20);
@endphp

@section('content')
<section class="hero-bg relative overflow-hidden pb-20 pt-12 text-white">
    <div class="pointer-events-none absolute -top-16 end-10 h-52 w-52 rounded-full bg-brand/20 blur-3xl" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-7xl px-4">
        <nav class="flex items-center gap-2 text-xs text-white/60" aria-label="{{ __('Breadcrumb') }}">
            <a href="{{ route('home') }}" class="hover:text-brand">{{ __('Home') }}</a><x-icon name="chevron-right" class="h-3 w-3 flip-rtl" /><span class="text-white">{{ __('Shop') }}</span>
        </nav>
        <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">{{ __('Cars for sale') }}</h1>
        <p class="mt-2 text-sm text-white/70"><span class="num font-bold text-brand-light">{{ number_format($cars->total()) }}</span> {{ __('cars match your search') }}</p>
    </div>
    <svg class="absolute inset-x-0 bottom-0 h-10 w-full text-white" viewBox="0 0 1200 40" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0 40V18c240 20 520 22 760 8S1100 4 1200 14v26Z"/></svg>
</section>

<section class="mx-auto grid max-w-7xl gap-8 px-4 py-10 lg:grid-cols-[300px_1fr]">
    {{-- ============ Filters ============ --}}
    <aside>
        <details class="card lg:hidden" @if($active->isNotEmpty()) open @endif>
            <summary class="flex items-center justify-between p-4 font-bold text-ink"><span class="flex items-center gap-2"><x-icon name="sliders" class="h-5 w-5 text-brand-dark" /> {{ __('Filters') }}</span><x-icon name="chevron-down" class="h-4 w-4" /></summary>
            <div class="border-t border-slate-100 p-4">@include('shop._filters')</div>
        </details>
        <div class="card sticky top-24 hidden p-5 lg:block">
            <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink"><x-icon name="sliders" class="h-5 w-5 text-brand-dark" /> {{ __('Filters') }}</h2>
            @include('shop._filters')
        </div>
    </aside>

    {{-- ============ Results ============ --}}
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-2">
                @foreach($active as $key => $value)
                    <a href="{{ route('shop', collect(request()->query())->except([$key, 'page'])->all()) }}" class="chip">
                        {{ in_array($key, ['governorate', 'traffic_unit', 'color', 'brand', 'body_type', 'transmission', 'fuel', 'license_type', 'origin'], true) ? t($value) : $value }}
                        <x-icon name="x" class="h-3 w-3" />
                    </a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('shop') }}" class="flex items-center gap-2">
                @foreach(request()->except(['sort', 'page']) as $k => $v)@if(is_string($v))<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif @endforeach
                <label for="sort" class="flex items-center gap-1.5 text-xs font-semibold text-slate-500"><x-icon name="repeat" class="h-4 w-4" /> {{ __('Sort') }}</label>
                <select id="sort" name="sort" data-autosubmit class="field !min-h-[40px] !w-auto !py-1.5 text-xs font-semibold">
                    @foreach($sorts as $value => $label)<option value="{{ $value }}" @selected(($filters['sort'] ?? 'newest') === $value)>{{ __($label) }}</option>@endforeach
                </select>
            </form>
        </div>

        @if($cars->isEmpty())
            <div class="card grid place-items-center px-6 py-20 text-center">
                <span class="grid h-16 w-16 place-items-center rounded-2xl bg-brand-50 text-brand-dark"><x-icon name="search" class="h-8 w-8" /></span>
                <h3 class="mt-5 text-lg font-extrabold text-ink">{{ __('No cars match your filters') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('Try removing a filter or searching a wider area.') }}</p>
                <a href="{{ route('shop') }}" class="btn btn-brand mt-6"><x-icon name="repeat" class="h-4 w-4" /> {{ __('Reset filters') }}</a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($cars as $car)@include('partials.car-card', ['car' => $car])@endforeach
            </div>
            @include('partials.pagination', ['paginator' => $cars])
        @endif
    </div>
</section>
@endsection
