@extends('layouts.app')

@section('title', $car->display_title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($car->description ?: $car->title.' - '.$car->governorate), 150))

@php
    $hasPhotos = $car->images->isNotEmpty();
    $views = ['side', 'front', 'rear'];
    $count = $hasPhotos ? $car->images->count() : count($views);
    $specs = [
        ['calendar', 'Year', $car->year],
        ['gauge', 'Mileage', number_format($car->mileage).' '.__('km')],
        ['settings', 'Gearbox', t($car->transmission)],
        ['fuel', 'Fuel', t($car->fuel)],
        ['zap', 'Engine', $car->engine_cc ? number_format($car->engine_cc).' cc' : '-'],
        ['route', 'Drive', $car->drive],
        ['palette', 'Colour', t($car->color) ?: '-'],
        ['car', 'Body', t($car->body_type)],
        ['users', 'Seats', $car->seats],
        ['award', 'Condition', t($car->condition)],
        ['key', 'Owners', $car->owners_count],
        ['shield-check', 'Accident free', $car->accident_free ? __('Yes') : __('No')],
    ];
    $licenseOk = $car->license_status === 'Valid';
@endphp

@section('content')
<section class="mx-auto max-w-7xl px-4 pt-8">
    <nav class="flex items-center gap-2 text-xs text-slate-500" aria-label="{{ __('Breadcrumb') }}">
        <a href="{{ route('home') }}" class="hover:text-brand-dark">{{ __('Home') }}</a><x-icon name="chevron-right" class="h-3 w-3 flip-rtl" />
        <a href="{{ route('shop') }}" class="hover:text-brand-dark">{{ __('Shop') }}</a><x-icon name="chevron-right" class="h-3 w-3 flip-rtl" />
        <span class="truncate font-semibold text-ink">{{ $car->display_title }}</span>
    </nav>

    <div class="mt-6 grid gap-8 lg:grid-cols-[1.45fr_1fr]">
        <div>
            {{-- ============ Gallery (transparent car art or uploaded photos) ============ --}}
            <div data-gallery tabindex="0" aria-label="{{ __('Car photos') }}" class="car-stage relative overflow-hidden rounded-3xl border border-slate-100">
                <div data-gal-track class="gal-track">
                    @if($hasPhotos)
                        @foreach($car->images as $i => $image)
                            <div class="gal-slide aspect-[16/10]"><img src="{{ $image->url }}" alt="{{ $car->display_title }} - {{ $i + 1 }}" class="h-full w-full object-cover" @if($i > 0) loading="lazy" @endif></div>
                        @endforeach
                    @else
                        @foreach($views as $v)
                            <div class="gal-slide grid aspect-[16/10] place-items-center px-6 pb-6 pt-10"><x-car-art :type="$car->body_type" :color="$car->color_hex" :view="$v" :label="$car->display_title.' - '.__($v)" class="car-art w-full max-w-[34rem]" /></div>
                        @endforeach
                    @endif
                </div>
                @if($count > 1)
                    <button type="button" data-gal-prev class="gal-arrow start-3" aria-label="{{ __('Previous photo') }}"><x-icon name="arrow-left" class="h-5 w-5 flip-rtl" /></button>
                    <button type="button" data-gal-next class="gal-arrow end-3" aria-label="{{ __('Next photo') }}"><x-icon name="arrow-right" class="h-5 w-5 flip-rtl" /></button>
                @endif
                <div class="absolute start-4 top-4 flex gap-2">
                    @if($car->status !== 'available')<span class="rounded-full bg-amber-400 px-3 py-1 text-xs font-extrabold text-amber-950">{{ __(ucfirst($car->status)) }}</span>@endif
                    @if($car->is_featured)<span class="inline-flex items-center gap-1 rounded-full bg-navy/85 px-3 py-1 text-xs font-bold text-white"><x-icon name="star" class="h-3 w-3 text-brand" /> {{ __('Featured') }}</span>@endif
                </div>
                @if($count > 1)
                    <div class="absolute inset-x-0 bottom-3 flex justify-center gap-1.5">
                        @for($i = 0; $i < $count; $i++)<button type="button" data-gal-dot class="gal-dot" aria-label="{{ __('Photo') }} {{ $i + 1 }}"></button>@endfor
                    </div>
                @endif
            </div>

            @if($count > 1)
                <div class="mt-3 flex gap-3 overflow-x-auto pb-1">
                    @if($hasPhotos)
                        @foreach($car->images as $i => $image)
                            <button type="button" data-gal-thumb class="gal-thumb h-16 w-24 shrink-0 overflow-hidden" aria-label="{{ __('Photo') }} {{ $i + 1 }}"><img src="{{ $image->url }}" alt="" class="h-full w-full object-cover"></button>
                        @endforeach
                    @else
                        @foreach($views as $i => $v)
                            <button type="button" data-gal-thumb class="gal-thumb car-stage grid h-16 w-24 shrink-0 place-items-center p-1.5" aria-label="{{ __($v) }}"><x-car-art :type="$car->body_type" :color="$car->color_hex" :view="$v" :shadow="false" class="w-full" /></button>
                        @endforeach
                    @endif
                </div>
            @endif

            {{-- ============ Specs ============ --}}
            <div class="card mt-8 p-6">
                <h2 class="mb-5 flex items-center gap-2 text-lg font-extrabold text-ink"><x-icon name="layers" class="h-5 w-5 text-brand-dark" /> {{ __('Specifications') }}</h2>
                <dl class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach($specs as [$icon, $label, $value])
                        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white text-brand-dark shadow-sm"><x-icon :name="$icon" class="h-5 w-5" /></span>
                            <div class="min-w-0"><dt class="text-[11px] text-slate-500">{{ __($label) }}</dt><dd class="num truncate text-[13px] font-extrabold text-ink">{{ $value }}</dd></div>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- ============ Egyptian licence ============ --}}
            <div class="card mt-6 p-6">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="flex items-center gap-2 text-lg font-extrabold text-ink"><x-icon name="id-card" class="h-5 w-5 text-brand-dark" /> {{ __('Licence & traffic unit') }}</h2>
                    <span class="pill {{ $licenseOk ? 'pill-available' : 'pill-reserved' }}"><x-icon :name="$licenseOk ? 'check-circle' : 'alert'" class="h-3.5 w-3.5" /> {{ __($car->license_status) }}</span>
                </div>
                <div class="grid gap-5 sm:grid-cols-[auto_1fr]">
                    {{-- Egyptian plate look-alike with hidden digits --}}
                    <div class="mx-auto w-full max-w-[210px] rounded-xl border-2 border-slate-800 bg-white p-1 text-ink shadow-md dark:border-slate-300" dir="rtl">
                        <div class="flex items-center justify-between rounded-md bg-sky-600 px-2 py-0.5 text-[9px] font-bold text-white"><span>مصر</span><span dir="ltr">EGYPT</span></div>
                        <div class="py-2 text-center text-xl font-extrabold tracking-wide text-slate-900"><span>{{ $car->plate_letters }}</span> <span class="text-slate-400">•••</span></div>
                        <p class="pb-1 text-center text-[9px] font-semibold text-slate-500">{{ __('Plate digits shared on request') }}</p>
                    </div>
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div><dt class="text-[11px] text-slate-500">{{ __('Traffic unit') }}</dt><dd class="font-extrabold text-ink">{{ t($car->traffic_unit) ?: '-' }}</dd></div>
                        <div><dt class="text-[11px] text-slate-500">{{ __('Licence type') }}</dt><dd class="font-extrabold text-ink">{{ __($car->license_type) }}</dd></div>
                        <div><dt class="text-[11px] text-slate-500">{{ __('Licence age') }}</dt><dd class="num font-extrabold text-ink">{{ $car->license_age ?? '-' }} {{ __('years') }}</dd></div>
                        <div><dt class="text-[11px] text-slate-500">{{ __('Licence valid for') }}</dt><dd class="num font-extrabold text-ink">{{ $car->license_valid_years }} {{ $car->license_valid_years === 1 ? __('year') : __('years') }}</dd></div>
                        <div><dt class="text-[11px] text-slate-500">{{ __('Expires') }}</dt><dd class="num font-extrabold text-ink">{{ $car->license_expiry?->format('M Y') ?? '-' }}</dd></div>
                        <div><dt class="text-[11px] text-slate-500">{{ __('Origin') }}</dt><dd class="font-extrabold text-ink">{{ $car->origin === 'Imported' ? __('Imported (customs cleared)') : __('Local (assembled in Egypt)') }}</dd></div>
                    </dl>
                </div>
            </div>

            @if($car->features)
                <div class="card mt-6 p-6">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-extrabold text-ink"><x-icon name="sparkles" class="h-5 w-5 text-brand-dark" /> {{ __('Features') }}</h2>
                    <ul class="flex flex-wrap gap-2">@foreach($car->features as $feature)<li class="chip"><x-icon name="check" class="h-3.5 w-3.5 text-emerald-600" /> {{ __($feature) }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                @foreach([['how_it_works', 'How it runs', 'gauge'], ['service_history', 'Service history', 'wrench'], ['papers', 'Paperwork', 'file-text'], ['description', 'Notes from the seller', 'edit']] as [$field, $title, $icon])
                    @if($car->{$field})
                        <div class="card p-6"><h2 class="mb-3 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon :name="$icon" class="h-5 w-5 text-brand-dark" /> {{ __($title) }}</h2>
                            <p class="whitespace-pre-line text-[13px] leading-7 text-slate-600">{{ $car->{$field} }}</p></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- ============ Summary / contact ============ --}}
        <aside>
            <div class="card sticky top-24 p-6">
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold">
                    <span class="chip"><x-icon name="pin" class="h-3 w-3 text-brand-dark" /> {{ t($car->governorate) }}{{ $car->city ? ' · '.t($car->city) : '' }}</span>
                    <span class="chip"><x-icon name="eye" class="h-3 w-3 text-brand-dark" /> <span class="num">{{ number_format($car->views) }}</span></span>
                </div>
                <h1 class="mt-4 text-2xl font-extrabold leading-snug text-ink">{{ $car->display_title }}</h1>
                <p class="num mt-2 text-3xl font-extrabold text-brand-dark">{{ egp($car->price) }}</p>
                <p class="mt-1 flex items-center gap-1.5 text-xs font-semibold {{ $car->negotiable ? 'text-emerald-600' : 'text-slate-500' }}"><x-icon name="percent" class="h-3.5 w-3.5" /> {{ $car->negotiable ? __('Price is negotiable') : __('Fixed price') }}</p>

                <div class="mt-6 grid gap-3">
                    <a href="{{ route('contact', ['car' => $car->slug]) }}" class="btn btn-primary"><x-icon name="mail" class="h-5 w-5" /> {{ __('Ask about this car') }}</a>
                    <a class="btn btn-wa" target="_blank" rel="noopener noreferrer" href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}?text={{ rawurlencode(__('Hello, I am interested in:').' '.$car->display_title) }}"><x-icon name="whatsapp" class="h-5 w-5" /> WhatsApp</a>
                    <a class="btn btn-ghost" href="tel:{{ preg_replace('/[^0-9+]/', '', config('turath.broker.phone')) }}"><x-icon name="phone" class="h-5 w-5" /> <span dir="ltr" class="num">{{ config('turath.broker.phone') }}</span></a>
                </div>

                <ul class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-[12px] text-slate-600">
                    <li class="flex items-start gap-2.5"><x-icon name="handshake" class="mt-0.5 h-4 w-4 shrink-0 text-brand-dark" /> {{ __('Listed through :name, an independent broker.', ['name' => __('Turath Cars')]) }}</li>
                    <li class="flex items-start gap-2.5"><x-icon name="shield-check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-dark" /> {{ __('Inspect at any garage you trust before you decide.') }}</li>
                </ul>
            </div>
        </aside>
    </div>

    @if($related->isNotEmpty())
        <div class="mt-20">
            <div class="mb-8"><span class="eyebrow">{{ __('Similar cars') }}</span><h2 class="mt-2 text-2xl font-extrabold text-ink">{{ __('You may also like') }}</h2><span class="bar mt-4"></span></div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach($related as $item)@include('partials.car-card', ['car' => $item])@endforeach</div>
        </div>
    @endif
</section>
@endsection
