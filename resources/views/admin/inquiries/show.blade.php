@extends('layouts.admin')

@section('title', __('Message'))

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-extrabold text-ink">{{ $inquiry->subject }}</h1>
    <a class="btn btn-ghost" href="{{ route('admin.inquiries.index') }}"><x-icon name="arrow-left" class="h-4 w-4 flip-rtl" /> {{ __('Back to messages') }}</a>
</div>

<article class="card max-w-3xl p-6 sm:p-8">
    <div class="flex items-center gap-4 border-b border-slate-100 pb-5">
        <span class="grid h-12 w-12 place-items-center rounded-full bg-brand-50 text-lg font-extrabold text-brand-dark">{{ mb_strtoupper(mb_substr($inquiry->name, 0, 1)) }}</span>
        <div class="min-w-0"><b class="block text-ink">{{ $inquiry->name }}</b>
            <p class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[12px] text-slate-500"><span class="inline-flex items-center gap-1" dir="ltr"><x-icon name="mail" class="h-3.5 w-3.5" /> {{ $inquiry->email }}</span>@if($inquiry->phone)<span class="inline-flex items-center gap-1" dir="ltr"><x-icon name="phone" class="h-3.5 w-3.5" /> {{ $inquiry->phone }}</span>@endif<span class="num inline-flex items-center gap-1"><x-icon name="clock" class="h-3.5 w-3.5" /> {{ $inquiry->created_at->format('d M Y, H:i') }}</span></p></div>
    </div>
    @if($inquiry->car)
        <a href="{{ route('shop.show', $inquiry->car) }}" target="_blank" rel="noopener" class="mt-5 flex items-center gap-4 rounded-2xl bg-brand-50 p-4">
            <div class="car-stage w-20 shrink-0 rounded-xl p-2"><x-car-art :type="$inquiry->car->body_type" :color="$inquiry->car->color_hex" :shadow="false" class="w-full" /></div>
            <div class="min-w-0"><p class="text-[11px] text-slate-500">{{ __('About this car') }}</p><p class="truncate text-sm font-extrabold text-ink">{{ $inquiry->car->display_title }}</p><p class="num text-xs font-bold text-brand-dark">{{ egp($inquiry->car->price) }}</p></div>
        </a>
    @endif
    <p class="prewrap mt-6 text-[15px] leading-8 text-slate-700">{{ $inquiry->message }}</p>
    <div class="mt-8 flex flex-wrap gap-3">
        <a class="btn btn-brand" href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Re: '.$inquiry->subject) }}"><x-icon name="send" class="h-5 w-5 flip-rtl" /> {{ __('Reply by email') }}</a>
        @if($inquiry->phone)<a class="btn btn-wa" href="https://wa.me/{{ preg_replace('/\D/', '', $inquiry->phone) }}" target="_blank" rel="noopener noreferrer"><x-icon name="whatsapp" class="h-5 w-5" /> WhatsApp</a>@endif
        <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" data-confirm="{{ __('Delete this message?') }}">@csrf @method('DELETE')
            <button class="btn btn-danger" type="submit"><x-icon name="trash" class="h-5 w-5" /> {{ __('Delete') }}</button></form>
    </div>
</article>
@endsection
