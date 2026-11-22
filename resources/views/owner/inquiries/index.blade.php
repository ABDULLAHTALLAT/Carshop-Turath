@extends('layouts.owner')

@section('title', __('Messages'))

@section('content')
<div class="mb-6"><h1 class="flex items-center gap-2 text-2xl font-extrabold text-ink"><x-icon name="inbox" class="h-6 w-6 text-brand-dark" /> {{ __('Messages') }}</h1>
    <p class="text-sm text-slate-500">{{ __('Buyers who asked about your cars. The broker replies to them for you.') }}</p></div>
<div class="card divide-y divide-slate-100 overflow-hidden">
    @forelse($inquiries as $inq)
        <div class="flex items-start gap-4 p-5">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-brand-50 font-extrabold text-brand-dark">{{ mb_strtoupper(mb_substr($inq->name, 0, 1)) }}</span>
            <div class="min-w-0 flex-1"><b class="block truncate text-sm text-ink">{{ $inq->subject }}</b>
                <p class="text-[12px] text-slate-500">{{ $inq->name }}@if($inq->car) &middot; {{ $inq->car->display_title }}@endif</p>
                <p class="mt-1 text-[13px] leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit($inq->message, 220) }}</p></div>
            <span class="num shrink-0 text-[11px] text-slate-400">{{ $inq->created_at->diffForHumans() }}</span>
        </div>
    @empty
        <p class="p-12 text-center text-slate-500">{{ __('No messages yet.') }}</p>
    @endforelse
</div>
<div class="mt-6">@include('partials.pagination', ['paginator' => $inquiries])</div>
@endsection
