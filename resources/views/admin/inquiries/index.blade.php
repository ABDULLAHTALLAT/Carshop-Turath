@extends('layouts.admin')

@section('title', __('Messages'))

@section('content')
<div class="mb-6">
    <h1 class="flex items-center gap-2 text-2xl font-extrabold text-ink"><x-icon name="inbox" class="h-6 w-6 text-brand-dark" /> {{ __('Messages') }}</h1>
    <p class="text-sm text-slate-500"><span class="num">{{ number_format($inquiries->total()) }}</span> {{ __('messages') }}</p>
</div>

<div class="card divide-y divide-slate-100 overflow-hidden">
    @forelse($inquiries as $inq)
        <a href="{{ route('admin.inquiries.show', $inq) }}" class="flex items-start gap-4 p-5 transition hover:bg-slate-50">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-brand-50 font-extrabold text-brand-dark">{{ mb_strtoupper(mb_substr($inq->name, 0, 1)) }}</span>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2"><b class="truncate text-sm text-ink">{{ $inq->subject }}</b>@if(is_null($inq->read_at))<span class="rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-extrabold text-white">{{ __('New') }}</span>@endif</div>
                <p class="truncate text-[12px] text-slate-500">{{ $inq->name }} &middot; <span dir="ltr">{{ $inq->email }}</span>@if($inq->car) &middot; {{ $inq->car->display_title }}@endif</p>
                <p class="mt-1 line-clamp-1 text-[12px] text-slate-400">{{ \Illuminate\Support\Str::limit($inq->message, 120) }}</p>
            </div>
            <span class="num shrink-0 text-[11px] text-slate-400">{{ $inq->created_at->diffForHumans() }}</span>
        </a>
    @empty
        <p class="p-12 text-center text-slate-500">{{ __('No messages yet.') }}</p>
    @endforelse
</div>
<div class="mt-6">@include('partials.pagination', ['paginator' => $inquiries])</div>
@endsection
