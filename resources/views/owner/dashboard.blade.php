@extends('layouts.owner')

@section('title', __('My dashboard'))

@php
    $kpiCards = [
        ['car', 'My cars', $kpis['total'], null, 'from-sky-400 to-blue-500', 'border-s-sky-400 from-sky-50'],
        ['check-circle', 'Live on the website', $kpis['live'], null, 'from-emerald-400 to-teal-500', 'border-s-emerald-400 from-emerald-50'],
        ['clock', 'Waiting for approval', $kpis['pending'], null, 'from-amber-400 to-orange-500', 'border-s-amber-400 from-amber-50'],
        ['handshake', 'Sold', $kpis['sold'], null, 'from-violet-400 to-fuchsia-500', 'border-s-violet-400 from-violet-50'],
        ['eye', 'Total views', $kpis['views'], null, 'from-cyan-400 to-sky-500', 'border-s-cyan-400 from-cyan-50'],
        ['inbox', 'Buyer messages', $kpis['messages'], null, 'from-indigo-400 to-blue-500', 'border-s-indigo-400 from-indigo-50'],
        ['banknote', 'Value of live cars', null, egp($kpis['value']), 'from-brand to-sky-500', 'border-s-sky-400 from-sky-50'],
    ];
    $pill = ['available' => 'pill-available', 'reserved' => 'pill-reserved', 'sold' => 'pill-sold', 'pending' => 'pill-reserved'];
@endphp

@section('content')
<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-ink">{{ __('Welcome') }}, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</h1>
        <p class="text-sm text-slate-500">{{ __('Follow your cars, views and buyer messages. The broker handles the buyers for you.') }}</p>
    </div>
    <a href="{{ route('owner.cars.create') }}" class="btn btn-brand"><x-icon name="plus" class="h-5 w-5" /> {{ __('Add car') }}</a>
</div>

@if($kpis['pending'])
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-900"><x-icon name="clock" class="h-5 w-5 shrink-0" /> {{ __('Some of your cars are waiting for the broker to approve them.') }}</div>
@endif

<div class="mb-8 grid grid-cols-2 gap-4 xl:grid-cols-4">
    @foreach($kpiCards as $i => [$icon, $label, $num, $text, $grad, $tint])
        <div class="kpi card reveal border-s-4 bg-gradient-to-br {{ $tint }} to-white p-5 d-{{ ($i % 4) + 1 }}">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br {{ $grad }} text-white shadow-lg"><x-icon :name="$icon" class="h-5 w-5" /></span>
            <p class="mt-4 text-[12px] font-semibold text-slate-500">{{ __($label) }}</p>
            @if($num !== null)<b class="num block text-2xl font-extrabold text-ink" data-count="{{ $num }}">0</b>@else<b class="num block truncate text-xl font-extrabold text-ink">{{ $text }}</b>@endif
        </div>
    @endforeach
</div>

<div class="mb-6 grid gap-6 xl:grid-cols-3">
    <section class="card p-6 xl:col-span-2">
        <div class="mb-4 flex items-center justify-between"><h2 class="flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="car" class="h-5 w-5 text-brand-dark" /> {{ __('My cars') }}</h2><a href="{{ route('owner.cars.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-dark">{{ __('All cars') }} <x-icon name="arrow-right" class="h-3.5 w-3.5 flip-rtl" /></a></div>
        @if($cars->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 p-10 text-center">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-brand-50 text-brand-dark"><x-icon name="car" class="h-7 w-7" /></span>
                <p class="mt-4 font-bold text-ink">{{ __('You have not added a car yet.') }}</p>
                <a href="{{ route('owner.cars.create') }}" class="btn btn-brand mt-4"><x-icon name="plus" class="h-5 w-5" /> {{ __('Add your first car') }}</a>
            </div>
        @else
            <div class="overflow-x-auto"><table class="w-full text-start text-sm">
                <thead><tr class="th border-b border-slate-100"><th class="py-2 text-start">{{ __('Car') }}</th><th class="py-2 text-start">{{ __('Price') }}</th><th class="py-2 text-start">{{ __('Views') }}</th><th class="py-2 text-start">{{ __('Status') }}</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @foreach($cars->take(6) as $car)
                    <tr><td class="py-3"><div class="flex items-center gap-3">
                        @if($car->cover_url)<img class="h-11 w-16 rounded-lg object-cover" src="{{ $car->cover_url }}" alt="" loading="lazy">@else<div class="car-stage w-16 shrink-0 rounded-lg p-1"><x-car-art :type="$car->body_type" :color="$car->color_hex" :shadow="false" class="w-full" /></div>@endif
                        <a href="{{ route('owner.cars.edit', $car) }}" class="truncate text-[13px] font-bold text-ink hover:text-brand-dark">{{ $car->display_title }}</a></div></td>
                        <td class="num py-3 text-[13px] font-bold">{{ egp($car->price) }}</td>
                        <td class="num py-3 text-[12px] text-slate-500">{{ number_format($car->views) }}</td>
                        <td class="py-3"><span class="pill {{ $pill[$car->status] ?? '' }}">{{ __(ucfirst($car->status)) }}</span></td></tr>
                @endforeach
                </tbody></table></div>
        @endif
    </section>
    <section class="card p-6">
        <h2 class="mb-4 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="layers" class="h-5 w-5 text-brand-dark" /> {{ __('Stock by status') }}</h2>
        @if($kpis['total'])@include('admin.charts.donut', ['data' => $status, 'palette' => ['#34d399', '#f59e0b', '#fbbf24', '#a78bfa'], 'label' => __('Stock by status')])@else<p class="text-sm text-slate-500">{{ __('No data yet.') }}</p>@endif
    </section>
</div>

<section class="card p-6">
    <div class="mb-4 flex items-center justify-between"><h2 class="flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="inbox" class="h-5 w-5 text-brand-dark" /> {{ __('Latest buyer messages') }}</h2><a href="{{ route('owner.inquiries.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-dark">{{ __('All') }} <x-icon name="arrow-right" class="h-3.5 w-3.5 flip-rtl" /></a></div>
    <ul class="divide-y divide-slate-100">
        @forelse($recent as $inq)
            <li class="flex items-center gap-3 py-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-50 text-xs font-extrabold text-brand-dark">{{ mb_strtoupper(mb_substr($inq->name, 0, 1)) }}</span>
                <span class="min-w-0 flex-1"><b class="block truncate text-[13px] text-ink">{{ $inq->subject }}</b><span class="block truncate text-[11px] text-slate-500">{{ $inq->name }} &middot; {{ $inq->created_at->diffForHumans() }}</span></span></li>
        @empty
            <li class="py-3 text-sm text-slate-500">{{ __('No messages yet.') }}</li>
        @endforelse
    </ul>
</section>
@endsection
