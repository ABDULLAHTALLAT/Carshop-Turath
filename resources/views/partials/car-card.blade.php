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
            <span class="absolute end-3 top-3 inline-flex items-center gap-1 rounded-full bg-white/80 px-2.5 py-1 text-[11px] font-bold text-ink backdrop-blur"><x-icon name="pin" class="h-3 w-3 text-brand-dark" /> {{ t($car->governorate) }}</span>
        </div>
        <div class="p-5">
            <h3 class="truncate text-[15px] font-extrabold text-ink">{{ $car->display_title }}</h3>
            <p class="mt-0.5 truncate text-xs text-slate-500">{{ t($car->body_type) }} &middot; {{ t($car->color) }}@if($car->traffic_unit) &middot; {{ t($car->traffic_unit) }}@endif</p>

            <dl class="mt-4 divide-y divide-slate-100 border-y border-slate-100 text-[12px]">
                @foreach([['gauge', 'Mileage', number_format($car->mileage).' '.__('km')], ['settings', 'Transmission', t($car->transmission)], ['id-card', 'Licence age', $car->license_age !== null ? $car->license_age.' '.__('years') : '-']] as [$icon, $label, $value])
                    <div class="flex items-center justify-between py-2">
                        <dt class="flex items-center gap-2 text-slate-500"><x-icon :name="$icon" class="h-4 w-4 text-brand-dark" /> {{ __($label) }}</dt>
                        <dd class="num font-bold text-ink">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="mt-4 flex items-center justify-between gap-3">
                <div>
                    <p class="num text-lg font-extrabold text-brand-dark">{{ egp($car->price) }}</p>
                    @if($car->negotiable)<p class="text-[10px] font-semibold text-emerald-600">{{ __('Price negotiable') }}</p>@endif
                </div>
                <span class="grid h-10 w-10 place-items-center rounded-full bg-brand-50 text-brand-dark transition group-hover:bg-navy group-hover:text-white"><x-icon name="arrow-right" class="h-5 w-5 flip-rtl" /></span>
            </div>
        </div>
    </a>
</article>
