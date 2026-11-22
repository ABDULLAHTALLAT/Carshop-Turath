@extends('layouts.admin')

@section('title', __('Overview'))

@php
    $k = $a['kpis'];
    $kpis = [
        ['car', 'Total cars', $k['total'], null, 'from-sky-400 to-blue-500', null],
        ['check-circle', 'Available', $k['available'], null, 'from-emerald-400 to-teal-500', null],
        ['clock', 'Reserved', $k['reserved'], null, 'from-amber-400 to-orange-500', null],
        ['handshake', 'Sold', $k['sold'], null, 'from-violet-400 to-fuchsia-500', null],
        ['banknote', 'Stock value', null, egp($k['stock_value']), 'from-brand to-sky-500', null],
        ['tag', 'Average price', null, egp($k['avg_price']), 'from-rose-400 to-pink-500', null],
        ['eye', 'Total views', $k['views'], null, 'from-cyan-400 to-sky-500', null],
        ['inbox', 'Messages (30 days)', $k['inq_30'], null, 'from-indigo-400 to-blue-500', $k['inq_delta']],
    ];
    $tints = ['border-s-sky-400 bg-gradient-to-br from-sky-50 to-white', 'border-s-emerald-400 bg-gradient-to-br from-emerald-50 to-white', 'border-s-amber-400 bg-gradient-to-br from-amber-50 to-white', 'border-s-violet-400 bg-gradient-to-br from-violet-50 to-white'];
    $attention = [
        ['inbox', $a['attention']['unread'], 'unread messages', route('admin.inquiries.index'), 'text-rose-600 bg-rose-50'],
        ['alert', $a['attention']['expired_licenses'], 'cars with an expired licence', route('admin.cars.index'), 'text-amber-600 bg-amber-50'],
        ['clock', $a['attention']['expiring'], 'licences expiring in 30 days', route('admin.cars.index'), 'text-sky-600 bg-sky-50'],
        ['handshake', $a['attention']['reserved'], 'reserved cars to close', route('admin.cars.index', ['status' => 'reserved']), 'text-violet-600 bg-violet-50'],
    ];
@endphp

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="flex items-center gap-2 text-xs font-semibold text-slate-500"><x-icon name="calendar" class="h-4 w-4" /> {{ now()->translatedFormat('l, d F Y') }}</p>
        <h1 class="mt-1 text-2xl font-extrabold text-ink">{{ __('Welcome back') }}, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</h1>
        <p class="text-sm text-slate-500">{{ __('Here is how your car business is doing.') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.cars.create') }}" class="btn btn-brand"><x-icon name="plus" class="h-5 w-5" /> {{ __('Add car') }}</a>
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-ghost"><x-icon name="inbox" class="h-5 w-5" /> {{ __('Messages') }}</a>
    </div>
</div>

{{-- Needs attention --}}
<div class="mb-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($attention as [$icon, $n, $label, $url, $tone])
        <a href="{{ $url }}" class="card flex items-center gap-4 p-4 {{ $n ? '' : 'opacity-60' }}">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl {{ $tone }}"><x-icon :name="$icon" class="h-5 w-5" /></span>
            <div class="min-w-0"><b class="num block text-xl font-extrabold text-ink">{{ $n }}</b><span class="block truncate text-[12px] text-slate-500">{{ __($label) }}</span></div>
            <x-icon name="arrow-right" class="ms-auto h-4 w-4 text-slate-300 flip-rtl" />
        </a>
    @endforeach
</div>

{{-- KPIs --}}
<div class="mb-8 grid grid-cols-2 gap-4 xl:grid-cols-4">
    @foreach($kpis as $i => [$icon, $label, $num, $text, $grad, $delta])
        <div class="kpi card reveal border-s-4 p-5 d-{{ ($i % 4) + 1 }} {{ $tints[$i % count($tints)] }}">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br {{ $grad }} text-white shadow-lg"><x-icon :name="$icon" class="h-5 w-5" /></span>
                @if($delta !== null)<span class="num inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $delta >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}"><x-icon :name="$delta >= 0 ? 'trending-up' : 'arrow-right'" class="h-3 w-3" /> {{ $delta > 0 ? '+' : '' }}{{ $delta }}%</span>@endif
            </div>
            <p class="mt-4 text-[12px] font-semibold text-slate-500">{{ __($label) }}</p>
            @if($num !== null)<b class="num block text-2xl font-extrabold text-ink" data-count="{{ $num }}">0</b>@else<b class="num block truncate text-xl font-extrabold text-ink">{{ $text }}</b>@endif
        </div>
    @endforeach
</div>

{{-- Charts row 1 --}}
<div class="mb-6 grid gap-6 xl:grid-cols-3">
    <section class="card p-6 xl:col-span-2">
        <div class="mb-4 flex items-center justify-between"><h2 class="flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="trending-up" class="h-5 w-5 text-brand-dark" /> {{ __('New listings per month') }}</h2><span class="chip">{{ __('Last 12 months') }}</span></div>
        @include('admin.charts.area', ['series' => $a['monthly'], 'id' => 'gArea', 'label' => __('New listings per month')])
    </section>
    <section class="card p-6">
        <h2 class="mb-4 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="layers" class="h-5 w-5 text-brand-dark" /> {{ __('Stock by status') }}</h2>
        @include('admin.charts.donut', ['data' => $a['status'], 'palette' => ['#34d399', '#f59e0b', '#a78bfa'], 'label' => __('Stock by status')])
    </section>
</div>

{{-- Charts row 2 --}}
<div class="mb-6 grid gap-6 lg:grid-cols-2">
    <section class="card p-6">
        <div class="mb-4 flex items-center justify-between"><h2 class="flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="inbox" class="h-5 w-5 text-brand-dark" /> {{ __('Messages per day') }}</h2><span class="chip">{{ __('Last 30 days') }}</span></div>
        @include('admin.charts.columns', ['series' => $a['daily_inquiries'], 'color' => '#6bbce9', 'label' => __('Messages per day')])
    </section>
    <section class="card p-6">
        <div class="mb-4 flex items-center justify-between"><h2 class="flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="banknote" class="h-5 w-5 text-brand-dark" /> {{ __('Price distribution (EGP)') }}</h2></div>
        @include('admin.charts.columns', ['series' => $a['price_buckets'], 'color' => '#3a9ad0', 'label' => __('Price distribution (EGP)')])
    </section>
</div>

{{-- Breakdowns --}}
<div class="mb-6 grid gap-6 lg:grid-cols-3">
    <section class="card p-6"><h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="tag" class="h-5 w-5 text-brand-dark" /> {{ __('Top brands') }}</h2>@include('admin.charts.hbars', ['data' => $a['brands'], 'translate' => true])</section>
    <section class="card p-6"><h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="map" class="h-5 w-5 text-brand-dark" /> {{ __('Top governorates') }}</h2>@include('admin.charts.hbars', ['data' => $a['governorates'], 'translate' => true])</section>
    <section class="card p-6"><h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="car" class="h-5 w-5 text-brand-dark" /> {{ __('Body types') }}</h2>@include('admin.charts.donut', ['data' => $a['body'], 'label' => __('Body types')])</section>
</div>

<div class="mb-6 grid gap-6 lg:grid-cols-3">
    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="palette" class="h-5 w-5 text-brand-dark" /> {{ __('Popular colours') }}</h2>
        <ul class="space-y-3">
            @php $cmax = max(1, max(array_values($a['colors']) ?: [1])); @endphp
            @foreach($a['colors'] as $name => $n)
                <li class="flex items-center gap-3"><span class="h-5 w-5 shrink-0 rounded-full ring-1 ring-slate-300" data-bg="{{ color_hex($name) }}"></span>
                    <span class="w-20 text-[12px] font-semibold text-slate-700">{{ t($name) }}</span>
                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-brand" data-w="{{ round($n / $cmax * 100) }}"></div></div>
                    <b class="num text-[12px] text-ink">{{ $n }}</b></li>
            @endforeach
        </ul>
    </section>
    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="id-card" class="h-5 w-5 text-brand-dark" /> {{ __('Licence status') }}</h2>
        @include('admin.charts.donut', ['data' => $a['license'], 'palette' => ['#34d399', '#f43f5e', '#f59e0b'], 'label' => __('Licence status')])
    </section>
    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="eye" class="h-5 w-5 text-brand-dark" /> {{ __('Most viewed') }}</h2>
        <ol class="space-y-3">
            @foreach($a['top_viewed'] as $i => $car)
                <li><a href="{{ route('shop.show', $car) }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-slate-50">
                    <span class="num grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brand-50 text-xs font-extrabold text-brand-dark">{{ $i + 1 }}</span>
                    <span class="min-w-0 flex-1"><b class="block truncate text-[13px] text-ink">{{ t($car->brand) }} {{ $car->model }} {{ $car->year }}</b><span class="num text-[11px] text-slate-500">{{ egp($car->price) }}</span></span>
                    <span class="num inline-flex items-center gap-1 text-[11px] font-bold text-slate-500"><x-icon name="eye" class="h-3.5 w-3.5" /> {{ number_format($car->views) }}</span></a></li>
            @endforeach
        </ol>
    </section>
</div>

{{-- Recent --}}
<div class="grid gap-6 xl:grid-cols-3">
    <section class="card p-6 xl:col-span-2">
        <div class="mb-4 flex items-center justify-between"><h2 class="flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="car" class="h-5 w-5 text-brand-dark" /> {{ __('Recently added cars') }}</h2><a href="{{ route('admin.cars.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-dark">{{ __('All cars') }} <x-icon name="arrow-right" class="h-3.5 w-3.5 flip-rtl" /></a></div>
        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm">
                <thead><tr class="th border-b border-slate-100 text-start"><th class="py-2 text-start">{{ __('Car') }}</th><th class="py-2 text-start">{{ __('Price') }}</th><th class="py-2 text-start">{{ __('Status') }}</th><th></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @foreach($a['recent_cars'] as $car)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3"><div class="flex items-center gap-3"><div class="car-stage w-16 shrink-0 rounded-lg p-1"><x-car-art :type="$car->body_type" :color="$car->color_hex" :shadow="false" class="w-full" /></div>
                            <div class="min-w-0"><b class="block truncate text-[13px] text-ink">{{ $car->display_title }}</b><span class="text-[11px] text-slate-500">{{ t($car->governorate) }}</span></div></div></td>
                        <td class="num py-3 text-[13px] font-bold text-ink">{{ egp($car->price) }}</td>
                        <td class="py-3"><span class="pill pill-{{ $car->status }}">{{ __(ucfirst($car->status)) }}</span></td>
                        <td class="py-3 text-end"><a href="{{ route('admin.cars.edit', $car) }}" class="btn btn-ghost btn-sm" aria-label="{{ __('Edit') }}"><x-icon name="edit" class="h-4 w-4" /></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <section class="card p-6">
        <div class="mb-4 flex items-center justify-between"><h2 class="flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="inbox" class="h-5 w-5 text-brand-dark" /> {{ __('Latest messages') }}</h2><a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-dark">{{ __('All') }} <x-icon name="arrow-right" class="h-3.5 w-3.5 flip-rtl" /></a></div>
        <ul class="space-y-1">
            @forelse($a['recent_inquiries'] as $inq)
                <li><a href="{{ route('admin.inquiries.show', $inq) }}" class="flex items-start gap-3 rounded-xl p-2.5 transition hover:bg-slate-50">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-50 text-xs font-extrabold text-brand-dark">{{ mb_strtoupper(mb_substr($inq->name, 0, 1)) }}</span>
                    <span class="min-w-0 flex-1"><b class="flex items-center gap-2 truncate text-[13px] text-ink">{{ $inq->subject }} @if(is_null($inq->read_at))<span class="h-2 w-2 shrink-0 rounded-full bg-rose-500"></span>@endif</b>
                        <span class="block truncate text-[11px] text-slate-500">{{ $inq->name }} &middot; {{ $inq->created_at->diffForHumans() }}</span></span></a></li>
            @empty
                <li class="text-sm text-slate-500">{{ __('No messages yet.') }}</li>
            @endforelse
        </ul>
    </section>
</div>
@endsection
