@extends('layouts.owner')

@section('title', __('My cars'))

@php $pill = ['available' => 'pill-available', 'reserved' => 'pill-reserved', 'sold' => 'pill-sold', 'pending' => 'pill-reserved']; @endphp

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div><h1 class="flex items-center gap-2 text-2xl font-extrabold text-ink"><x-icon name="car" class="h-6 w-6 text-brand-dark" /> {{ __('My cars') }}</h1>
        <p class="text-sm text-slate-500"><span class="num">{{ $cars->total() }}</span> {{ __('listings') }}</p></div>
    <a class="btn btn-brand" href="{{ route('owner.cars.create') }}"><x-icon name="plus" class="h-5 w-5" /> {{ __('Add car') }}</a>
</div>

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
@forelse($cars as $car)
    <article class="card overflow-hidden">
        <div class="car-stage relative aspect-[16/10]">
            @if($car->cover_url)<img src="{{ $car->cover_url }}" alt="" class="h-full w-full object-cover" loading="lazy">@else<div class="grid h-full place-items-center px-6 pt-4"><x-car-art :type="$car->body_type" :color="$car->color_hex" class="w-full max-w-[16rem]" /></div>@endif
            <span class="pill pill-{{ $car->status }} absolute start-3 top-3">{{ __(ucfirst($car->status)) }}</span>
        </div>
        <div class="p-5">
            <h3 class="truncate font-extrabold text-ink">{{ $car->display_title }}</h3>
            <p class="num mt-1 text-sm font-extrabold text-brand-dark">{{ egp($car->price) }}</p>
            <p class="mt-1 flex items-center gap-1 text-[11px] text-slate-500"><x-icon name="eye" class="h-3.5 w-3.5" /> <span class="num">{{ number_format($car->views) }}</span> {{ __('Views') }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a class="btn btn-ghost btn-sm" href="{{ route('owner.cars.edit', $car) }}"><x-icon name="edit" class="h-4 w-4" /> {{ __('Edit') }}</a>
                @if($car->status !== 'sold' && $car->status !== 'pending')
                    <form method="POST" action="{{ route('owner.cars.sold', $car) }}" data-confirm="{{ __('Mark this car as sold?') }}">@csrf @method('PATCH')<button class="btn btn-ghost btn-sm" type="submit"><x-icon name="handshake" class="h-4 w-4" /> {{ __('Sold') }}</button></form>
                @endif
                <form method="POST" action="{{ route('owner.cars.destroy', $car) }}" data-confirm="{{ __('Delete this car and its photos?') }}">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit" aria-label="{{ __('Delete') }}"><x-icon name="trash" class="h-4 w-4" /></button></form>
            </div>
        </div>
    </article>
@empty
    <p class="card col-span-full p-12 text-center text-slate-500">{{ __('You have not added a car yet.') }}</p>
@endforelse
</div>
<div class="mt-6">@include('partials.pagination', ['paginator' => $cars])</div>
@endsection
