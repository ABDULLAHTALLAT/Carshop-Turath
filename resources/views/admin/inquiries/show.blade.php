@extends('layouts.admin')

@section('title', 'Message')

@section('content')
<div class="admin-top">
    <h1 class="h-md">{{ $inquiry->subject }}</h1>
    <a class="btn btn-ghost" href="{{ route('admin.inquiries.index') }}">Back</a>
</div>

<article class="card panel">
    <p class="muted">
        From <strong>{{ $inquiry->name }}</strong> &lt;{{ $inquiry->email }}&gt;
        @if($inquiry->phone) &middot; {{ $inquiry->phone }} @endif
        &middot; {{ $inquiry->created_at->format('d M Y, H:i') }}
    </p>
    @if($inquiry->car)
        <p>About: <a href="{{ route('shop.show', $inquiry->car) }}">{{ $inquiry->car->title }}</a></p>
    @endif
    <p class="prewrap">{{ $inquiry->message }}</p>

    <div class="row-actions">
        <a class="btn btn-primary" href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Re: '.$inquiry->subject) }}">Reply by email</a>
        <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" data-confirm="Delete this message?">
            @csrf @method('DELETE')
            <button class="btn btn-danger" type="submit">Delete</button>
        </form>
    </div>
</article>
@endsection
