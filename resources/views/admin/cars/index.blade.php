@extends('layouts.admin')

@section('title', __('Cars'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="flex items-center gap-2 text-2xl font-extrabold text-ink"><x-icon name="car" class="h-6 w-6 text-brand-dark" /> {{ __('Cars') }}</h1>
        <p class="text-sm text-slate-500"><span class="num">{{ number_format($cars->total()) }}</span> {{ __('listings') }}</p>
    </div>
    <a class="btn btn-brand" href="{{ route('admin.cars.create') }}"><x-icon name="plus" class="h-5 w-5" /> {{ __('Add car') }}</a>
</div>

<form method="GET" class="card mb-6 flex flex-wrap items-center gap-3 p-4" role="search">
    <div class="relative min-w-[220px] flex-1">
        <x-icon name="search" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
        <input class="field ps-9" type="search" name="q" value="{{ $search }}" maxlength="80" placeholder="{{ __('Search cars...') }}" aria-label="{{ __('Search cars...') }}">
    </div>
    <select name="status" data-autosubmit class="field !w-auto" aria-label="{{ __('Status') }}">
        <option value="">{{ __('All statuses') }}</option>
        @foreach(\App\Models\Car::STATUSES as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ __(ucfirst($s)) }}</option>
        @endforeach
    </select>
    <button class="btn btn-ghost" type="submit"><x-icon name="search" class="h-4 w-4" /> {{ __('Search') }}</button>
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-start text-sm">
            <thead class="bg-slate-50"><tr class="th">
                <th class="px-5 py-3 text-start">{{ __('Car') }}</th><th class="px-3 py-3 text-start">{{ __('Location') }}</th><th class="px-3 py-3 text-start">{{ __('Price') }}</th><th class="px-3 py-3 text-start">{{ __('Views') }}</th><th class="px-3 py-3 text-start">{{ __('Status') }}</th><th class="px-5 py-3"></th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($cars as $car)
                <tr class="hover:bg-slate-50/70">
                    <td class="px-5 py-3"><div class="flex items-center gap-3">
                        @if($car->cover_url)<img class="h-12 w-16 rounded-lg object-cover" src="{{ $car->cover_url }}" alt="" loading="lazy">
                        @else<div class="car-stage w-16 shrink-0 rounded-lg p-1"><x-car-art :type="$car->body_type" :color="$car->color_hex" :shadow="false" class="w-full" /></div>@endif
                        <div class="min-w-0"><b class="block truncate text-[13px] text-ink">{{ $car->display_title }}</b><span class="num text-[11px] text-slate-500">{{ $car->year }} &middot; {{ number_format($car->mileage) }} km</span></div></div></td>
                    <td class="px-3 py-3 text-[12px] text-slate-600"><span class="block">{{ t($car->governorate) }}</span><span class="text-[11px] text-slate-400">{{ $car->traffic_unit ? t($car->traffic_unit) : '' }}</span></td>
                    <td class="num px-3 py-3 text-[13px] font-bold text-ink">{{ egp($car->price) }}</td>
                    <td class="num px-3 py-3 text-[12px] text-slate-500">{{ number_format($car->views) }}</td>
                    <td class="px-3 py-3">
                        <form method="POST" action="{{ route('admin.cars.status', $car) }}">@csrf @method('PATCH')
                            <select name="status" data-autosubmit class="field !min-h-[34px] !w-auto !py-1 text-[12px]" aria-label="{{ __('Status') }}">
                                @foreach(\App\Models\Car::STATUSES as $s)<option value="{{ $s }}" @selected($car->status === $s)>{{ __(ucfirst($s)) }}</option>@endforeach
                            </select></form></td>
                    <td class="px-5 py-3"><div class="flex justify-end gap-1.5">
                        <a class="btn btn-ghost btn-sm" href="{{ route('shop.show', $car) }}" target="_blank" rel="noopener" aria-label="{{ __('View') }}"><x-icon name="eye" class="h-4 w-4" /></a>
                        <a class="btn btn-ghost btn-sm" href="{{ route('admin.cars.edit', $car) }}" aria-label="{{ __('Edit') }}"><x-icon name="edit" class="h-4 w-4" /></a>
                        <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" data-confirm="{{ __('Delete this car and its photos?') }}">@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit" aria-label="{{ __('Delete') }}"><x-icon name="trash" class="h-4 w-4" /></button></form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">{{ __('No cars found.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">@include('partials.pagination', ['paginator' => $cars])</div>
@endsection
