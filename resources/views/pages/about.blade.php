@extends('layouts.app')

@section('title', 'About')

@section('content')
<section class="section">
    <div class="container narrow">
        <span class="eyebrow">About us</span>
        <h1>An independent broker for used cars in Egypt</h1>
        <p class="muted">{{ config('turath.broker.name') }} is a simple marketplace. Owners list their cars with the details buyers actually care about, and we connect the two sides. We are brokers only: we do not own the cars on this site.</p>

        <div class="grid mt-lg">
            <div class="card panel"><h3>Clear listings</h3><p class="muted">Every car shows its paperwork, license status, service history and how it runs.</p></div>
            <div class="card panel"><h3>Honest brokerage</h3><p class="muted">We never hide who owns the car. You always know you are dealing with a broker.</p></div>
            <div class="card panel"><h3>Across Egypt</h3><p class="muted">Cars from Cairo and Giza to Alexandria, the Delta and Upper Egypt.</p></div>
        </div>

        <h2 class="mt-xl">Want to sell your car?</h2>
        <p class="muted">Send us the details and a few photos. We will review them, prepare the listing and handle enquiries from serious buyers.</p>
        <a class="btn btn-primary" href="{{ route('contact') }}">Contact us</a>
    </div>
</section>
@endsection
