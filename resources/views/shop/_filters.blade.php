<form method="GET" action="{{ route('shop') }}" data-search data-count-url="{{ route('shop.count') }}" class="space-y-4">
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? '' }}">

    <div><label class="label" for="f-q">{{ __('Search') }}</label>
        <div class="relative"><x-icon name="search" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
        <input id="f-q" class="field ps-9" name="q" value="{{ $filters['q'] ?? '' }}" maxlength="80" placeholder="{{ __('Brand, model or area') }}"></div></div>

    <div class="grid grid-cols-2 gap-3">
        <div><label class="label" for="f-body">{{ __('Type') }}</label>
            <select id="f-body" class="field" name="body_type"><option value="">{{ __('All') }}</option>
                @foreach(\App\Models\Car::BODY_TYPES as $b)<option value="{{ $b }}" @selected(($filters['body_type'] ?? '') === $b)>{{ __($b) }}</option>@endforeach</select></div>
        <div><label class="label" for="f-brand">{{ __('Brand') }}</label>
            <select id="f-brand" class="field" name="brand"><option value="">{{ __('All') }}</option>
                @foreach($brands as $b)<option value="{{ $b }}" @selected(($filters['brand'] ?? '') === $b)>{{ t($b) }}</option>@endforeach</select></div>
    </div>

    <div><label class="label" for="f-gov"><x-icon name="pin" class="me-1 inline h-3.5 w-3.5 text-brand-dark" />{{ __('Governorate') }}</label>
        <select id="f-gov" class="field" name="governorate"><option value="">{{ __('Any governorate') }}</option>
            @foreach(\App\Models\Car::governorates() as $g)<option value="{{ $g }}" @selected(($filters['governorate'] ?? '') === $g)>{{ t($g) }}</option>@endforeach</select></div>

    <div><label class="label" for="f-unit"><x-icon name="id-card" class="me-1 inline h-3.5 w-3.5 text-brand-dark" />{{ __('Traffic unit') }}</label>
        <select id="f-unit" class="field" name="traffic_unit"><option value="">{{ __('Any traffic unit') }}</option>
            @foreach($units as $gov => $list)@foreach($list as $u)<option value="{{ $u }}" data-gov="{{ $gov }}" @selected(($filters['traffic_unit'] ?? '') === $u)>{{ t($u) }}</option>@endforeach @endforeach</select></div>

    <div><span class="label"><x-icon name="palette" class="me-1 inline h-3.5 w-3.5 text-brand-dark" />{{ __('Colour') }}</span>
        <div class="flex flex-wrap gap-2" data-swatches>
            <label class="cursor-pointer"><input type="radio" class="swatch-input sr-only-input" name="color" value="" @checked(empty($filters['color'])) data-name="{{ __('Any colour') }}"><span class="swatch grid place-items-center bg-white text-slate-400" title="{{ __('Any colour') }}"><x-icon name="x" class="h-3 w-3" /></span></label>
            @foreach(\App\Support\EgyptData::COLORS as $name => [$hex, $ar])
                <label class="cursor-pointer"><input type="radio" class="swatch-input sr-only-input" name="color" value="{{ $name }}" @checked(($filters['color'] ?? '') === $name) data-name="{{ t($name) }}"><span class="swatch {{ 'bg-['.$hex.']' }}" title="{{ t($name) }}"></span></label>
            @endforeach
        </div>
        <p class="mt-2 text-[11px] text-slate-500" data-swatch-label>{{ ! empty($filters['color']) ? t($filters['color']) : __('Any colour') }}</p></div>

    <div><span class="label"><x-icon name="banknote" class="me-1 inline h-3.5 w-3.5 text-brand-dark" />{{ __('Price (EGP)') }}</span>
        <div class="grid grid-cols-2 gap-3">
            <input class="field num" type="number" min="0" step="10000" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="{{ __('From') }}" aria-label="{{ __('Min price') }}">
            <input class="field num" type="number" min="0" step="10000" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="{{ __('To') }}" aria-label="{{ __('Max price') }}">
        </div></div>

    <div><span class="label"><x-icon name="calendar" class="me-1 inline h-3.5 w-3.5 text-brand-dark" />{{ __('Model year') }}</span>
        <div class="grid grid-cols-2 gap-3">
            <select class="field num" name="min_year" aria-label="{{ __('From year') }}"><option value="">{{ __('From') }}</option>@foreach($years as $y)<option value="{{ $y }}" @selected((int) ($filters['min_year'] ?? 0) === $y)>{{ $y }}</option>@endforeach</select>
            <select class="field num" name="max_year" aria-label="{{ __('To year') }}"><option value="">{{ __('To') }}</option>@foreach($years as $y)<option value="{{ $y }}" @selected((int) ($filters['max_year'] ?? 0) === $y)>{{ $y }}</option>@endforeach</select>
        </div></div>

    <div><label class="label" for="f-lic"><x-icon name="key" class="me-1 inline h-3.5 w-3.5 text-brand-dark" />{{ __('Licence age') }}</label>
        <select id="f-lic" class="field" name="license_max_age"><option value="">{{ __('Any licence age') }}</option>
            @foreach([1, 2, 3, 5, 8, 10] as $a)<option value="{{ $a }}" @selected((int) ($filters['license_max_age'] ?? 0) === $a)>{{ __('Licensed within') }} {{ $a }} {{ $a === 1 ? __('year') : __('years') }}</option>@endforeach</select></div>

    <div class="grid grid-cols-2 gap-3">
        <div><label class="label" for="f-lt">{{ __('Licence type') }}</label>
            <select id="f-lt" class="field" name="license_type"><option value="">{{ __('All') }}</option>@foreach(\App\Models\Car::LICENSE_TYPES as $t)<option value="{{ $t }}" @selected(($filters['license_type'] ?? '') === $t)>{{ __($t) }}</option>@endforeach</select></div>
        <div><label class="label" for="f-or">{{ __('Origin') }}</label>
            <select id="f-or" class="field" name="origin"><option value="">{{ __('All') }}</option>@foreach(\App\Models\Car::ORIGINS as $o)<option value="{{ $o }}" @selected(($filters['origin'] ?? '') === $o)>{{ __($o) }}</option>@endforeach</select></div>
        <div><label class="label" for="f-tr">{{ __('Gearbox') }}</label>
            <select id="f-tr" class="field" name="transmission"><option value="">{{ __('All') }}</option>@foreach(\App\Models\Car::TRANSMISSIONS as $t)<option value="{{ $t }}" @selected(($filters['transmission'] ?? '') === $t)>{{ __($t) }}</option>@endforeach</select></div>
        <div><label class="label" for="f-fu">{{ __('Fuel') }}</label>
            <select id="f-fu" class="field" name="fuel"><option value="">{{ __('All') }}</option>@foreach(\App\Models\Car::FUELS as $t)<option value="{{ $t }}" @selected(($filters['fuel'] ?? '') === $t)>{{ __($t) }}</option>@endforeach</select></div>
    </div>

    <div><label class="label" for="f-km"><x-icon name="gauge" class="me-1 inline h-3.5 w-3.5 text-brand-dark" />{{ __('Max mileage (km)') }}</label>
        <input id="f-km" class="field num" type="number" min="0" step="5000" name="max_mileage" value="{{ $filters['max_mileage'] ?? '' }}"></div>

    <div class="flex gap-2 pt-1">
        <button type="submit" class="sb-search flex-1 !h-12"><x-icon name="search" class="h-4 w-4" /> {{ __('Show cars') }} <b data-count-out>{{ $cars->total() }}</b></button>
        <a href="{{ route('shop') }}" class="btn btn-ghost !px-3" aria-label="{{ __('Reset filters') }}"><x-icon name="repeat" class="h-4 w-4" /></a>
    </div>
</form>
