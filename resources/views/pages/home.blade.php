@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">Used cars in Egypt</span>
            <h1>Find a car you can trust, with every detail upfront.</h1>
            <p class="lead">Each listing shows the paperwork, how the car runs and its full history, so you can decide with confidence before you ever visit.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="{{ route('shop') }}">Browse cars</a>
                <a class="btn btn-ghost" href="{{ route('contact') }}">Sell your car</a>
            </div>
            <div class="stats">
                <div class="stat"><strong>{{ $stats['cars'] }}</strong><span class="muted">Cars available</span></div>
                <div class="stat"><strong>{{ $stats['brands'] }}</strong><span class="muted">Brands</span></div>
                <div class="stat"><strong>{{ $stats['sold'] }}</strong><span class="muted">Cars sold</span></div>
            </div>
        </div>
        <div class="hero-art" aria-hidden="true">
            <svg viewBox="0 0 480 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M30 150c0-16 10-26 26-30l62-14c14-22 34-38 62-42 38-6 82-2 112 18l22 14 56 12c18 4 30 16 30 34v10c0 6-5 10-10 10h-26a40 40 0 0 0-80 0H174a40 40 0 0 0-80 0H40c-6 0-10-4-10-10v-2z" fill="currentColor" fill-opacity=".08" stroke="currentColor" stroke-opacity=".35" stroke-width="2"/>
                <circle cx="134" cy="172" r="30" fill="currentColor" fill-opacity=".1" stroke="currentColor" stroke-opacity=".4" stroke-width="2"/>
                <circle cx="134" cy="172" r="12" fill="currentColor" fill-opacity=".25"/>
                <circle cx="358" cy="172" r="30" fill="currentColor" fill-opacity=".1" stroke="currentColor" stroke-opacity=".4" stroke-width="2"/>
                <circle cx="358" cy="172" r="12" fill="currentColor" fill-opacity=".25"/>
                <path d="M170 108l16-26c12-8 30-10 52-10h30c20 0 36 6 50 18l14 18z" fill="currentColor" fill-opacity=".12"/>
            </svg>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Fresh listings</span>
                <h2>Latest cars</h2>
            </div>
            <a class="btn btn-ghost btn-sm" href="{{ route('shop') }}">View all</a>
        </div>

        @if($featured->isEmpty())
            <div class="card empty">No cars are listed yet. Please check back soon.</div>
        @else
            <div class="grid">
                @foreach($featured as $car)
                    @include('partials.car-card', ['car' => $car])
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="section">
    <div class="container">
        <span class="eyebrow">How it works</span>
        <h2>Simple from first look to handover</h2>
        <div class="steps">
            <div class="card step"><h3>Browse</h3><p class="muted">Filter by brand, price, year and governorate to narrow the list quickly.</p></div>
            <div class="card step"><h3>Review the details</h3><p class="muted">Check photos, paperwork, service history and how the car runs.</p></div>
            <div class="card step"><h3>Contact us</h3><p class="muted">Send a message or use WhatsApp and we will arrange a viewing.</p></div>
            <div class="card step"><h3>Close the deal</h3><p class="muted">We help both sides with paperwork checks and a fair handover.</p></div>
        </div>
    </div>
</section>
@endsection
