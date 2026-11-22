@php $slug = $car->slug; @endphp
<article class="card group overflow-hidden">
    <a href="{{ route('shop.show', $car) }}" class="block" aria-label="{{ $car->display_title }}">
        <div class="car-stage relative aspect-[16/10] overflow-hidden">
            @if($car->images->isNotEmpty())
                <img src="{{ $car->cover_url }}" alt="{{ $car->display_title }}" loading="lazy" class="h-full w-full object-cover opacity-95 transition duration-500 group-hover:scale-105">
            @else
                <div class="grid h-full place-items-center px-6 pb-3 pt-6">
                    <x-car-art :type="$car->body_type" :color="$car->color_hex" :label="$car->display_title" class="car-art w-full max-w-[19rem]" />
                </div>
            @endif
            <div class="absolute start-3 top-3 flex gap-2">
                @if($car->status === 'reserved')<span class="rounded-full bg-amber-400 px-3 py-1 text-[11px] font-extrabold text-amber-950">{{ __('Reserved') }}</span>@endif
                @if($car->is_featured)<span class="inline-flex items-center gap-1 rounded-full bg-navy/85 px-3 py-1 text-[11px] font-bold text-white backdrop-blur"><x-icon name="star" class="h-3 w-3 text-brand" /> {{ __('Featured') }}</span>@endif
            </div>
            <span class="absolute end-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/80 px-2.5 py-1 text-[11px] font-bold text-ink backdrop-blur dark:bg-slate-900/70 dark:text-white"><x-icon name="pin" class="h-3 w-3 text-brand-dark" /> {{ t($car->governorate) }}</span>
        </div>
        <div class="p-5">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="truncate text-[15px] font-extrabold text-ink">{{ $car->display_title }}</h3>
                    <p class="mt-0.5 truncate text-xs text-slate-500">{{ t($car->body_type) }} &middot; {{ t($car->color) }}</p>
                </div>
                <p class="num whitespace-nowrap text-end text-base font-extrabold text-brand-dark">{{ egp($car->price) }}</p>
            </div>
            <ul class="mt-4 grid grid-cols-3 gap-2 border-t border-slate-100 pt-4 text-[11px] font-semibold text-slate-600">
                <li class="flex flex-col items-center gap-1"><x-icon name="calendar" class="h-4 w-4 text-brand-dark" /> <span class="num">{{ $car->year }}</span></li>
                <li class="flex flex-col items-center gap-1"><x-icon name="gauge" class="h-4 w-4 text-brand-dark" /> <span class="num">{{ number_format($car->mileage) }} {{ __('km') }}</span></li>
                <li class="flex flex-col items-center gap-1"><x-icon name="settings" class="h-4 w-4 text-brand-dark" /> {{ t($car->transmission) }}</li>
            </ul>
            @if($car->traffic_unit)
                <p class="mt-3 flex items-center gap-1.5 truncate text-[11px] text-slate-500"><x-icon name="id-card" class="h-3.5 w-3.5 text-brand-dark" /> {{ t($car->traffic_unit) }}</p>
            @endif
        </div>
    </a>
</article>
