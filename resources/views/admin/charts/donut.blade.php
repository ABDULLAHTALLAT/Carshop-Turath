@php
    $palette = $palette ?? ['#0b2447', '#3a9ad0', '#6bbce9', '#a8d9f3', '#e0a526', '#34d399', '#f472b6', '#94a3b8'];
    $total = max(1, array_sum($data));
    $offset = 0; $i = 0;
@endphp
<div class="flex flex-wrap items-center gap-6">
    <div class="relative h-40 w-40 shrink-0">
    <svg viewBox="0 0 42 42" class="h-40 w-40 -rotate-90" role="img" aria-label="{{ $label ?? '' }}">
        <circle cx="21" cy="21" r="15.915" fill="none" class="chart-grid" stroke-width="5"/>
        @foreach($data as $name => $v)
            @php $pct = $v / $total * 100; @endphp
            @if($v > 0)
                <circle class="chart-bar" cx="21" cy="21" r="15.915" fill="none" stroke="{{ $palette[$i % count($palette)] }}" stroke-width="5" stroke-dasharray="{{ round($pct, 2) }} {{ round(100 - $pct, 2) }}" stroke-dashoffset="{{ -round($offset, 2) }}"><title>{{ __($name) }}: {{ $v }}</title></circle>
            @endif
            @php $offset += $pct; $i++; @endphp
        @endforeach
    </svg>
    <span class="num absolute inset-0 grid place-items-center text-2xl font-extrabold text-ink">{{ array_sum($data) }}</span>
    </div>
    <ul class="min-w-[9rem] flex-1 space-y-2 text-[12px]">
        @php $i = 0; @endphp
        @foreach($data as $name => $v)
            <li class="flex items-center justify-between gap-3"><span class="flex items-center gap-2 text-slate-600"><span class="h-2.5 w-2.5 rounded-full" data-bg="{{ $palette[$i % count($palette)] }}"></span>{{ __($name) }}</span><b class="num text-ink">{{ $v }}</b></li>
            @php $i++; @endphp
        @endforeach
    </ul>
</div>
