@extends(($mode ?? 'admin') === 'owner' ? 'layouts.owner' : 'layouts.admin')

@php
    $p = $mode ?? 'admin';
    $editing = $car->exists;
    $v = fn ($key, $default = '') => old($key, $car->{$key} ?? $default);
    $featuresText = old('features', is_array($car->features) ? implode(', ', $car->features) : '');
@endphp
@php
    $governorates = \App\Models\Car::governorates();
    $unitsByGov = \App\Models\Car::trafficUnits();
    $colors = \App\Models\Car::colors();
    $sel = fn ($key, $list, $tr = true) => collect($list)->map(fn ($o) => '<option value="'.e($o).'"'.((string) $v($key) === (string) $o ? ' selected' : '').'>'.e($tr ? t($o) : $o).'</option>')->implode('');
@endphp

@section('title', $editing ? __('Edit car') : __('Add car'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="flex items-center gap-2 text-2xl font-extrabold text-ink"><x-icon :name="$editing ? 'edit' : 'plus'" class="h-6 w-6 text-brand-dark" /> {{ $editing ? __('Edit car') : __('Add car') }}</h1>
    <a class="btn btn-ghost" href="{{ route($p.'.cars.index') }}"><x-icon name="arrow-left" class="h-4 w-4 flip-rtl" /> {{ __('Back to cars') }}</a>
</div>

@if($errors->any())
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700" role="alert"><x-icon name="alert" class="h-5 w-5" /> {{ __('Please fix the highlighted fields below.') }}</div>
@endif

<form method="POST" enctype="multipart/form-data" action="{{ $editing ? route($p.'.cars.update', $car) : route($p.'.cars.store') }}" class="space-y-6">
    @csrf
    @if($editing) @method('PUT') @endif

    @php $err = fn ($k) => $errors->first($k); @endphp

    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="car" class="h-5 w-5 text-brand-dark" /> {{ __('Basic details') }}</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="sm:col-span-2 lg:col-span-3"><label class="label" for="title">{{ __('Title') }}</label><input id="title" class="field" name="title" value="{{ $v('title') }}" placeholder="Hyundai Elantra 2019" required>@if($err('title'))<p class="field-error">{{ $err('title') }}</p>@endif</div>
            <div><label class="label" for="brand">{{ __('Brand') }}</label><input id="brand" class="field" name="brand" value="{{ $v('brand') }}" required>@if($err('brand'))<p class="field-error">{{ $err('brand') }}</p>@endif</div>
            <div><label class="label" for="model">{{ __('Model') }}</label><input id="model" class="field" name="model" value="{{ $v('model') }}" required>@if($err('model'))<p class="field-error">{{ $err('model') }}</p>@endif</div>
            <div><label class="label" for="year">{{ __('Year') }}</label><input id="year" class="field num" type="number" name="year" value="{{ $v('year') }}" required>@if($err('year'))<p class="field-error">{{ $err('year') }}</p>@endif</div>
            <div><label class="label" for="price">{{ __('Price (EGP)') }}</label><input id="price" class="field num" type="number" name="price" value="{{ $v('price') }}" required>@if($err('price'))<p class="field-error">{{ $err('price') }}</p>@endif</div>
            <div><label class="label" for="mileage">{{ __('Mileage (km)') }}</label><input id="mileage" class="field num" type="number" name="mileage" value="{{ $v('mileage') }}" required>@if($err('mileage'))<p class="field-error">{{ $err('mileage') }}</p>@endif</div>
            <div><label class="label" for="engine_cc">{{ __('Engine (cc)') }}</label><input id="engine_cc" class="field num" type="number" name="engine_cc" value="{{ $v('engine_cc') }}">@if($err('engine_cc'))<p class="field-error">{{ $err('engine_cc') }}</p>@endif</div>
            <div><label class="label" for="body_type">{{ __('Body type') }}</label><select id="body_type" class="field" name="body_type">{!! $sel('body_type', \App\Models\Car::BODY_TYPES) !!}</select></div>
            <div><label class="label" for="transmission">{{ __('Transmission') }}</label><select id="transmission" class="field" name="transmission">{!! $sel('transmission', \App\Models\Car::TRANSMISSIONS) !!}</select></div>
            <div><label class="label" for="fuel">{{ __('Fuel') }}</label><select id="fuel" class="field" name="fuel">{!! $sel('fuel', \App\Models\Car::FUELS) !!}</select></div>
            <div><label class="label" for="drive">{{ __('Drive') }}</label><select id="drive" class="field" name="drive">{!! $sel('drive', \App\Models\Car::DRIVES, false) !!}</select></div>
            <div><label class="label" for="seats">{{ __('Seats') }}</label><input id="seats" class="field num" type="number" name="seats" value="{{ $v('seats', 5) }}" required></div>
            <div><label class="label" for="color">{{ __('Colour') }}</label><select id="color" class="field" name="color"><option value="">-</option>{!! $sel('color', $colors) !!}</select></div>
            <div><label class="label" for="condition">{{ __('Condition') }}</label><select id="condition" class="field" name="condition">{!! $sel('condition', \App\Models\Car::CONDITIONS) !!}</select></div>
            <div><label class="label" for="origin">{{ __('Origin') }}</label><select id="origin" class="field" name="origin">{!! $sel('origin', \App\Models\Car::ORIGINS) !!}</select></div>
            <div><label class="label" for="owners_count">{{ __('Previous owners') }}</label><input id="owners_count" class="field num" type="number" name="owners_count" value="{{ $v('owners_count', 1) }}" required></div>
        </div>
    </section>

    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="map" class="h-5 w-5 text-brand-dark" /> {{ __('Location and traffic unit') }}</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div><label class="label" for="governorate">{{ __('Governorate') }}</label>
                <select id="governorate" class="field" name="governorate" data-form-gov>
                    @foreach($governorates as $g)<option value="{{ $g }}" @selected($v('governorate') === $g)>{{ t($g) }}</option>@endforeach
                </select>@if($err('governorate'))<p class="field-error">{{ $err('governorate') }}</p>@endif</div>
            <div><label class="label" for="city">{{ __('City / area') }}</label><input id="city" class="field" name="city" value="{{ $v('city') }}">@if($err('city'))<p class="field-error">{{ $err('city') }}</p>@endif</div>
            <div><label class="label" for="traffic_unit">{{ __('Traffic unit') }}</label>
                <select id="traffic_unit" class="field" name="traffic_unit" data-form-unit>
                    <option value="">-</option>
                    @foreach($unitsByGov as $g => $units)@foreach($units as $u)<option value="{{ $u }}" data-gov="{{ $g }}" @selected($v('traffic_unit') === $u)>{{ t($u) }}</option>@endforeach @endforeach
                </select>@if($err('traffic_unit'))<p class="field-error">{{ $err('traffic_unit') }}</p>@endif</div>
        </div>
    </section>

    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="id-card" class="h-5 w-5 text-brand-dark" /> {{ __('Licence and plate') }}</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div><label class="label" for="plate_letters">{{ __('Plate letters') }}</label><input id="plate_letters" class="field" name="plate_letters" value="{{ $v('plate_letters') }}" maxlength="16" placeholder="أ ب ج">@if($err('plate_letters'))<p class="field-error">{{ $err('plate_letters') }}</p>@endif</div>
            <div><label class="label" for="plate_numbers">{{ __('Plate numbers') }}</label><input id="plate_numbers" class="field num" name="plate_numbers" value="{{ $v('plate_numbers') }}" maxlength="8" inputmode="numeric" dir="ltr">@if($err('plate_numbers'))<p class="field-error">{{ $err('plate_numbers') }}</p>@endif</div>
            <div><label class="label" for="license_type">{{ __('Licence type') }}</label><select id="license_type" class="field" name="license_type">{!! $sel('license_type', \App\Models\Car::LICENSE_TYPES) !!}</select></div>
            <div><label class="label" for="license_status">{{ __('Licence status') }}</label><select id="license_status" class="field" name="license_status">{!! $sel('license_status', \App\Models\Car::LICENSE_STATUSES) !!}</select></div>
            <div><label class="label" for="licensed_since">{{ __('Licensed since (year)') }}</label><input id="licensed_since" class="field num" type="number" name="licensed_since" value="{{ $v('licensed_since') }}">@if($err('licensed_since'))<p class="field-error">{{ $err('licensed_since') }}</p>@endif</div>
            <div><label class="label" for="license_valid_years">{{ __('Licence validity (years)') }}</label><select id="license_valid_years" class="field" name="license_valid_years">@foreach([1,2,3] as $y)<option value="{{ $y }}" @selected((int) $v('license_valid_years', 3) === $y)>{{ $y }}</option>@endforeach</select></div>
            <div><label class="label" for="license_expiry">{{ __('Licence expiry') }}</label><input id="license_expiry" class="field" type="date" name="license_expiry" value="{{ old('license_expiry', optional($car->license_expiry)->format('Y-m-d')) }}" dir="ltr">@if($err('license_expiry'))<p class="field-error">{{ $err('license_expiry') }}</p>@endif</div>
            <label class="flex items-center gap-3 self-end rounded-xl border border-slate-200 p-3 text-sm font-semibold"><input type="checkbox" name="accident_free" value="1" class="h-4 w-4 accent-sky-500" @checked(old('accident_free', $car->accident_free))> {{ __('Accident free') }}</label>
        </div>
    </section>

    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="file-text" class="h-5 w-5 text-brand-dark" /> {{ __('Description and papers') }}</h2>
        <div class="grid gap-5">
            <div><label class="label" for="how_it_works">{{ __('How the car runs') }}</label><textarea id="how_it_works" class="field" name="how_it_works">{{ $v('how_it_works') }}</textarea></div>
            <div><label class="label" for="papers">{{ __('Paperwork') }}</label><textarea id="papers" class="field" name="papers">{{ $v('papers') }}</textarea></div>
            <div><label class="label" for="service_history">{{ __('Service history') }}</label><textarea id="service_history" class="field" name="service_history">{{ $v('service_history') }}</textarea></div>
            <div><label class="label" for="description">{{ __('Notes') }}</label><textarea id="description" class="field" name="description">{{ $v('description') }}</textarea></div>
            <div><label class="label" for="features">{{ __('Features (comma separated)') }}</label><textarea id="features" class="field" name="features" placeholder="ABS, Sunroof, Rear camera">{{ $featuresText }}</textarea>@if($err('features'))<p class="field-error">{{ $err('features') }}</p>@endif</div>
        </div>
    </section>

    @if($p === 'admin')
    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="user" class="h-5 w-5 text-brand-dark" /> {{ __('Seller (private) and status') }}</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div><label class="label" for="seller_name">{{ __('Seller name') }}</label><input id="seller_name" class="field" name="seller_name" value="{{ $v('seller_name') }}" required>@if($err('seller_name'))<p class="field-error">{{ $err('seller_name') }}</p>@endif</div>
            <div><label class="label" for="seller_phone">{{ __('Seller phone') }}</label><input id="seller_phone" class="field" name="seller_phone" value="{{ $v('seller_phone') }}" required dir="ltr">@if($err('seller_phone'))<p class="field-error">{{ $err('seller_phone') }}</p>@endif</div>
            <div><label class="label" for="status">{{ __('Status') }}</label><select id="status" class="field" name="status">@foreach(\App\Models\Car::STATUSES as $s)<option value="{{ $s }}" @selected($v('status') === $s)>{{ __(ucfirst($s)) }}</option>@endforeach</select></div>
            <div class="grid gap-2 self-end">
                <label class="flex items-center gap-3 text-sm font-semibold"><input type="checkbox" name="negotiable" value="1" class="h-4 w-4 accent-sky-500" @checked(old('negotiable', $car->negotiable))> {{ __('Price negotiable') }}</label>
                <label class="flex items-center gap-3 text-sm font-semibold"><input type="checkbox" name="is_featured" value="1" class="h-4 w-4 accent-sky-500" @checked(old('is_featured', $car->is_featured))> {{ __('Featured on home page') }}</label>
            </div>
        </div>
    </section>

    @else
    <div class="flex items-start gap-3 rounded-2xl border border-sky-200 bg-sky-50 p-5 text-sm text-sky-900"><x-icon name="shield-check" class="mt-0.5 h-5 w-5 shrink-0" /> <p>{{ __('After you save, the broker reviews your car and publishes it. Your phone number stays private.') }}</p></div>
    @endif

    <section class="card p-6">
        <h2 class="mb-5 flex items-center gap-2 text-[15px] font-extrabold text-ink"><x-icon name="image" class="h-5 w-5 text-brand-dark" /> {{ __('Photos') }}</h2>
        @if($editing && $car->images->isNotEmpty())
            <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                @foreach($car->images as $image)
                    <figure class="overflow-hidden rounded-xl border border-slate-200"><img class="aspect-[4/3] w-full object-cover" src="{{ $image->url }}" alt="{{ __('Photo') }} {{ $loop->iteration }}" loading="lazy">
                        <button class="btn btn-danger btn-sm w-full !rounded-none" type="submit" form="del-{{ $image->id }}"><x-icon name="trash" class="h-4 w-4" /> {{ __('Remove') }}</button></figure>
                @endforeach
            </div>
        @endif
        <label class="label" for="images">{{ __('Add photos') }} <span class="font-normal text-slate-400">(JPG, PNG, WebP &middot; {{ config('turath.uploads.max_images') }} max &middot; {{ config('turath.uploads.max_kb') / 1024 }} MB)</span></label>
        <input id="images" class="field" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
        @if($err('images'))<p class="field-error">{{ $err('images') }}</p>@endif
        @if($err('images.0'))<p class="field-error">{{ $err('images.0') }}</p>@endif
    </section>

    <div class="flex justify-end gap-3">
        <a class="btn btn-ghost" href="{{ route($p.'.cars.index') }}">{{ __('Cancel') }}</a>
        <button class="btn btn-brand" type="submit"><x-icon name="check-circle" class="h-5 w-5" /> {{ $editing ? __('Save changes') : __('Create car') }}</button>
    </div>
</form>

@if($editing)
    @foreach($car->images as $image)
        <form id="del-{{ $image->id }}" method="POST" action="{{ route($p.'.cars.images.destroy', [$car, $image]) }}" data-confirm="{{ __('Remove this photo?') }}">@csrf @method('DELETE')</form>
    @endforeach
@endif
@endsection
