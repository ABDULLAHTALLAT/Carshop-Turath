@php
    $f = $filters ?? [];
    $tabs = [['', 'All', 'layers'], ['Sedan', 'Sedan', 'car'], ['SUV', 'SUV', 'car'], ['Hatchback', 'Hatchback', 'car'], ['Pickup', 'Pickup', 'car'], ['Van', 'Van', 'users']];
    $units = \App\Models\Car::trafficUnits();
    $prices = [250000, 500000, 750000, 1000000, 1500000, 2000000, 3000000];
    $ages = [1, 2, 3, 5, 8, 10];
    $current = $f['body_type'] ?? '';
@endphp
<form method="GET" action="{{ route('shop') }}" data-search data-count-url="{{ route('shop.count') }}" class="sb mx-auto max-w-6xl overflow-hidden rounded-3xl shadow-2xl shadow-navy/20 ring-1 ring-slate-200/70 dark:ring-slate-700">
    <input type="hidden" name="body_type" value="{{ $current }}">

    <div class="sb-tabs px-3 sm:px-5" role="tablist" aria-label="{{ __('Car type') }}">
        @foreach($tabs as [$value, $label, $icon])
            <button type="button" role="tab" data-tab="{{ $value }}" aria-selected="{{ $current === $value ? 'true' : 'false' }}" class="sb-tab {{ $current === $value ? 'is-on' : '' }}">
                <x-icon :name="$icon" class="h-4 w-4" /> {{ __($label) }}
            </button>
        @endforeach
    </div>

    <div class="grid gap-3 p-4 sm:p-5 md:grid-cols-2 lg:grid-cols-12">
        <label class="sb-box lg:col-span-4">
            <x-icon name="search" class="h-5 w-5 shrink-0 text-brand-dark" />
            <input type="search" name="q" value="{{ $f['q'] ?? '' }}" maxlength="80" placeholder="{{ __('Brand, model or area') }}" aria-label="{{ __('Search') }}">
        </label>

        <label class="sb-box sb-sel lg:col-span-4">
            <x-icon name="pin" class="h-5 w-5 shrink-0 text-brand-dark" />
            <select name="governorate" aria-label="{{ __('Governorate') }}">
                <option value="">{{ __('Any governorate') }}</option>
                @foreach(\App\Models\Car::governorates() as $g)
                    <option value="{{ $g }}" @selected(($f['governorate'] ?? '') === $g)>{{ t($g) }}</option>
                @endforeach
            </select>
            <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-slate-400" />
        </label>

        <label class="sb-box sb-sel lg:col-span-4">
            <x-icon name="id-card" class="h-5 w-5 shrink-0 text-brand-dark" />
            <select name="traffic_unit" aria-label="{{ __('Traffic unit') }}">
                <option value="">{{ __('Any traffic unit') }}</option>
                @foreach($units as $gov => $list)
                    @foreach($list as $u)
                        <option value="{{ $u }}" data-gov="{{ $gov }}" @selected(($f['traffic_unit'] ?? '') === $u)>{{ t($u) }}</option>
                    @endforeach
                @endforeach
            </select>
            <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-slate-400" />
        </label>

        <label class="sb-box sb-sel lg:col-span-3">
            <x-icon name="tag" class="h-5 w-5 shrink-0 text-brand-dark" />
            <select name="brand" aria-label="{{ __('Brand') }}">
                <option value="">{{ __('Any brand') }}</option>
                @foreach($brands as $b)
                    <option value="{{ $b }}" @selected(($f['brand'] ?? '') === $b)>{{ t($b) }}</option>
                @endforeach
            </select>
            <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-slate-400" />
        </label>

        <label class="sb-box sb-sel lg:col-span-3">
            <x-icon name="palette" class="h-5 w-5 shrink-0 text-brand-dark" />
            <select name="color" aria-label="{{ __('Colour') }}">
                <option value="">{{ __('Any colour') }}</option>
                @foreach(\App\Models\Car::colors() as $c)
                    <option value="{{ $c }}" @selected(($f['color'] ?? '') === $c)>{{ t($c) }}</option>
                @endforeach
            </select>
            <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-slate-400" />
        </label>

        <label class="sb-box sb-sel lg:col-span-3">
            <x-icon name="banknote" class="h-5 w-5 shrink-0 text-brand-dark" />
            <select name="max_price" aria-label="{{ __('Max price') }}">
                <option value="">{{ __('Any price') }}</option>
                @foreach($prices as $p)
                    <option value="{{ $p }}" @selected((int) ($f['max_price'] ?? 0) === $p)>{{ __('Up to') }} {{ number_format($p) }}</option>
                @endforeach
            </select>
            <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-slate-400" />
        </label>

        <label class="sb-box sb-sel lg:col-span-3">
            <x-icon name="key" class="h-5 w-5 shrink-0 text-brand-dark" />
            <select name="license_max_age" aria-label="{{ __('Licence age') }}">
                <option value="">{{ __('Any licence age') }}</option>
                @foreach($ages as $a)
                    <option value="{{ $a }}" @selected((int) ($f['license_max_age'] ?? 0) === $a)>{{ __('Licensed within') }} {{ $a }} {{ $a === 1 ? __('year') : __('years') }}</option>
                @endforeach
            </select>
            <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-slate-400" />
        </label>

        <div class="md:col-span-2 lg:col-span-12 lg:flex lg:justify-end">
            <button type="submit" class="sb-search w-full lg:w-auto lg:min-w-[220px]">
                <x-icon name="search" class="h-5 w-5" :stroke="2.2" />
                {{ __('Show cars') }}
                <b data-count-out>{{ $total ?? '' }}</b>
            </button>
        </div>
    </div>
</form>
