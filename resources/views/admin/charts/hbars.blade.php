@php $max = max(1, max(array_values($data) ?: [1])); @endphp
<ul class="space-y-3">
    @forelse($data as $name => $v)
        <li>
            <div class="mb-1 flex items-center justify-between text-[12px]"><span class="font-semibold text-slate-700">{{ ! empty($translate) ? t($name) : $name }}</span><b class="num text-ink">{{ $v }}</b></div>
            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100"><div class="grow-x h-full rounded-full brand-gradient" data-w="{{ round($v / $max * 100) }}"></div></div>
        </li>
    @empty
        <li class="text-sm text-slate-500">{{ __('No data yet.') }}</li>
    @endforelse
</ul>
