@extends('layouts.admin')

@php
    $editing = $car->exists;
    $v = fn ($key, $default = '') => old($key, $car->{$key} ?? $default);
    $options = fn ($list, $key) => collect($list)->map(fn ($o) => '<option value="'.e($o).'"'.($v($key) === $o ? ' selected' : '').'>'.e($o).'</option>')->implode('');
@endphp

@section('title', $editing ? 'Edit car' : 'Add car')

@section('content')
<div class="admin-top">
    <h1 class="h-md">{{ $editing ? 'Edit '.$car->title : 'Add a car' }}</h1>
    <a class="btn btn-ghost" href="{{ route('admin.cars.index') }}">Back</a>
</div>

@if($errors->any())
    <div class="alert" role="alert">Please fix the highlighted fields below.</div>
@endif

<form class="card panel" method="POST" enctype="multipart/form-data"
      action="{{ $editing ? route('admin.cars.update', $car) : route('admin.cars.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="form-grid">
        <div class="field full"><label for="title">Title</label>
            <input id="title" name="title" value="{{ $v('title') }}" placeholder="e.g. Hyundai Elantra 2019" required>
            @error('title')<span class="error">{{ $message }}</span>@enderror</div>

        <div class="field"><label for="brand">Brand</label><input id="brand" name="brand" value="{{ $v('brand') }}" required>@error('brand')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="model">Model</label><input id="model" name="model" value="{{ $v('model') }}" required>@error('model')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="year">Year</label><input id="year" type="number" name="year" value="{{ $v('year') }}" required>@error('year')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="price">Price (EGP)</label><input id="price" type="number" name="price" value="{{ $v('price') }}" required>@error('price')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="mileage">Mileage (km)</label><input id="mileage" type="number" name="mileage" value="{{ $v('mileage') }}" required>@error('mileage')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="engine_cc">Engine (cc)</label><input id="engine_cc" type="number" name="engine_cc" value="{{ $v('engine_cc') }}">@error('engine_cc')<span class="error">{{ $message }}</span>@enderror</div>

        <div class="field"><label for="body_type">Body type</label><select id="body_type" name="body_type">{!! $options(\App\Models\Car::BODY_TYPES, 'body_type') !!}</select></div>
        <div class="field"><label for="transmission">Transmission</label><select id="transmission" name="transmission">{!! $options(\App\Models\Car::TRANSMISSIONS, 'transmission') !!}</select></div>
        <div class="field"><label for="fuel">Fuel</label><select id="fuel" name="fuel">{!! $options(\App\Models\Car::FUELS, 'fuel') !!}</select></div>
        <div class="field"><label for="condition">Condition</label><select id="condition" name="condition">{!! $options(\App\Models\Car::CONDITIONS, 'condition') !!}</select></div>
        <div class="field"><label for="color">Color</label><input id="color" name="color" value="{{ $v('color') }}"></div>
        <div class="field"><label for="governorate">Governorate</label><select id="governorate" name="governorate">{!! $options(\App\Models\Car::GOVERNORATES, 'governorate') !!}</select></div>

        <div class="field"><label for="license_status">License status</label><select id="license_status" name="license_status">{!! $options(\App\Models\Car::LICENSE_STATUSES, 'license_status') !!}</select></div>
        <div class="field"><label for="license_expiry">License expiry</label><input id="license_expiry" type="date" name="license_expiry" value="{{ old('license_expiry', optional($car->license_expiry)->format('Y-m-d')) }}">@error('license_expiry')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="owners_count">Previous owners</label><input id="owners_count" type="number" name="owners_count" value="{{ $v('owners_count', 1) }}" required></div>
        <div class="field"><label for="status">Status</label><select id="status" name="status">{!! $options(\App\Models\Car::STATUSES, 'status') !!}</select></div>

        <div class="field check"><input id="accident_free" type="checkbox" name="accident_free" value="1" @checked(old('accident_free', $car->accident_free))><label for="accident_free">Accident free</label></div>
        <div class="field check"><input id="is_featured" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $car->is_featured))><label for="is_featured">Featured on home page</label></div>

        <div class="field full"><label for="how_it_works">How the car runs</label><textarea id="how_it_works" name="how_it_works">{{ $v('how_it_works') }}</textarea></div>
        <div class="field full"><label for="papers">Paperwork (license, ownership, customs, etc.)</label><textarea id="papers" name="papers">{{ $v('papers') }}</textarea></div>
        <div class="field full"><label for="service_history">Service history</label><textarea id="service_history" name="service_history">{{ $v('service_history') }}</textarea></div>
        <div class="field full"><label for="description">Description</label><textarea id="description" name="description">{{ $v('description') }}</textarea></div>

        <div class="field"><label for="seller_name">Seller name (private)</label><input id="seller_name" name="seller_name" value="{{ $v('seller_name') }}" required>@error('seller_name')<span class="error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="seller_phone">Seller phone (private)</label><input id="seller_phone" name="seller_phone" value="{{ $v('seller_phone') }}" required>@error('seller_phone')<span class="error">{{ $message }}</span>@enderror</div>
    </div>

    @if($editing && $car->images->isNotEmpty())
        <h3 class="h-sm">Current photos</h3>
        <div class="photo-edit">
            @foreach($car->images as $image)
                <figure>
                    <img src="{{ $image->url }}" alt="Photo {{ $loop->iteration }}">
                    <button class="btn btn-danger btn-sm btn-block" type="submit" form="del-{{ $image->id }}">Remove</button>
                </figure>
            @endforeach
        </div>
    @endif

    <div class="field">
        <label for="images">Add photos (JPG, PNG or WebP, up to {{ config('turath.uploads.max_images') }} photos, {{ config('turath.uploads.max_kb') / 1024 }} MB each)</label>
        <input id="images" type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
        @error('images')<span class="error">{{ $message }}</span>@enderror
        @error('images.*')<span class="error">{{ $message }}</span>@enderror
    </div>

    <button class="btn btn-primary" type="submit">{{ $editing ? 'Save changes' : 'Create car' }}</button>
</form>

@if($editing)
    @foreach($car->images as $image)
        <form id="del-{{ $image->id }}" method="POST" action="{{ route('admin.cars.images.destroy', [$car, $image]) }}" data-confirm="Remove this photo?">
            @csrf @method('DELETE')
        </form>
    @endforeach
@endif
@endsection
