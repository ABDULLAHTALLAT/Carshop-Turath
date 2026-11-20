@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<section class="section">
    <div class="container narrow-lg">
        <span class="eyebrow">Contact</span>
        <h1>Let's talk about your next car</h1>
        <p class="muted">Ask about a listing, or tell us about the car you want to sell.</p>

        @if(session('status'))
            <div class="alert success" role="status">{{ session('status') }}</div>
        @endif

        <div class="two-col">
            <form class="card panel" method="POST" action="{{ route('contact.store') }}" novalidate>
                @csrf
                @if($car)
                    <input type="hidden" name="car" value="{{ $car->slug }}">
                    <p class="muted">About: <strong>{{ $car->title }}</strong></p>
                @endif

                <div class="hp" aria-hidden="true">
                    <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="field">
                    <label for="name">Your name</label>
                    <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required>
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                    @error('email')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="phone">Phone (optional)</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                    @error('phone')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="subject">Subject</label>
                    <input id="subject" name="subject" value="{{ old('subject', $car ? 'Question about '.$car->title : '') }}" required>
                    @error('subject')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required>{{ old('message') }}</textarea>
                    @error('message')<span class="error">{{ $message }}</span>@enderror
                </div>
                <button class="btn btn-primary" type="submit">Send message</button>
            </form>

            <aside class="card panel">
                <h3>Reach us directly</h3>
                <p class="muted">{{ config('turath.broker.hours') }}</p>
                <p><strong>Phone</strong><br>{{ config('turath.broker.phone') }}</p>
                <p><strong>Email</strong><br>{{ config('turath.broker.email') }}</p>
                <a class="btn btn-whatsapp" href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}" rel="noopener noreferrer" target="_blank">Chat on WhatsApp</a>
            </aside>
        </div>
    </div>
</section>
@endsection
