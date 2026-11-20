@extends('layouts.app')

@section('title', $car->title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($car->description ?: $car->title.' for sale in '.$car->governorate), 150))

@section('content')
<section class="section">
    <div class="container">
        <p class="muted"><a href="{{ route('shop') }}">&larr; Back to shop</a></p>

        <div class="detail">
            <div>
                <div class="slider" data-slider tabindex="0" aria-label="Car photos">
                    @if($car->images->isEmpty())
                        <div class="card car-media"><span class="no-photo">No photo yet</span></div>
                    @else
                        <div class="slider-track" data-slider-track>
                            @foreach($car->images as $i => $image)
                                <div class="slide"><img src="{{ $image->url }}" alt="{{ $car->title }} - photo {{ $i + 1 }}" @if($i > 0) loading="lazy" @endif></div>
                            @endforeach
                        </div>
                        @if($car->images->count() > 1)
                            <button class="slider-btn prev" type="button" data-slider-prev aria-label="Previous photo">&#8249;</button>
                            <button class="slider-btn next" type="button" data-slider-next aria-label="Next photo">&#8250;</button>
                            <div class="slider-dots">
                                @foreach($car->images as $i => $image)
                                    <button type="button" data-slider-dot aria-label="Go to photo {{ $i + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="thumbs">
                                @foreach($car->images as $i => $image)
                                    <button type="button" data-slider-thumb aria-label="Show photo {{ $i + 1 }}"><img src="{{ $image->url }}" alt=""></button>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <div class="tabs-block">
                    @if($car->how_it_works)
                        <div class="card"><h3>How it runs</h3><p class="prose">{{ $car->how_it_works }}</p></div>
                    @endif
                    @if($car->papers)
                        <div class="card"><h3>Paperwork</h3><p class="prose">{{ $car->papers }}</p></div>
                    @endif
                    @if($car->service_history)
                        <div class="card"><h3>Service history</h3><p class="prose">{{ $car->service_history }}</p></div>
                    @endif
                    @if($car->description)
                        <div class="card"><h3>Description</h3><p class="prose">{{ $car->description }}</p></div>
                    @endif
                </div>
            </div>

            <aside class="card panel">
                @if($car->status !== 'available')
                    <span class="status-pill {{ $car->status }}">{{ ucfirst($car->status) }}</span>
                @endif
                <h1 class="title-lg">{{ $car->title }}</h1>
                <div class="price title-lg">EGP {{ number_format($car->price) }}</div>

                <dl class="spec-list spec-gap">
                    <div><dt>Year</dt><dd>{{ $car->year }}</dd></div>
                    <div><dt>Mileage</dt><dd>{{ number_format($car->mileage) }} km</dd></div>
                    <div><dt>Body</dt><dd>{{ $car->body_type }}</dd></div>
                    <div><dt>Gearbox</dt><dd>{{ $car->transmission }}</dd></div>
                    <div><dt>Fuel</dt><dd>{{ $car->fuel }}</dd></div>
                    <div><dt>Engine</dt><dd>{{ $car->engine_cc ? number_format($car->engine_cc).' cc' : '-' }}</dd></div>
                    <div><dt>Color</dt><dd>{{ $car->color ?: '-' }}</dd></div>
                    <div><dt>Condition</dt><dd>{{ $car->condition }}</dd></div>
                    <div><dt>Location</dt><dd>{{ $car->governorate }}</dd></div>
                    <div><dt>License</dt><dd>{{ $car->license_status }}@if($car->license_expiry) (to {{ $car->license_expiry->format('M Y') }})@endif</dd></div>
                    <div><dt>Owners</dt><dd>{{ $car->owners_count }}</dd></div>
                    <div><dt>Accident free</dt><dd>{{ $car->accident_free ? 'Yes' : 'No' }}</dd></div>
                </dl>

                <p class="muted hint-top">Listed through {{ config('turath.broker.name') }}, an independent broker.</p>
                <a class="btn btn-primary" href="{{ route('contact', ['car' => $car->slug]) }}">Ask about this car</a>
                <a class="btn btn-whatsapp mt-sm" target="_blank" rel="noopener noreferrer"
                   href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}?text={{ rawurlencode('Hello, I am interested in: '.$car->title) }}">WhatsApp</a>
            </aside>
        </div>

        @if($related->isNotEmpty())
            <h2 class="mt-xl">You may also like</h2>
            <div class="grid">
                @foreach($related as $item)
                    @include('partials.car-card', ['car' => $item])
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
