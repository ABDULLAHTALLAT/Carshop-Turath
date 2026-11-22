@if($paginator->hasPages())
    <nav class="mt-12 flex flex-wrap items-center justify-center gap-2" aria-label="{{ __('Pages') }}">
        @if($paginator->onFirstPage())
            <span class="pg-arrow is-off" aria-hidden="true"><x-icon name="arrow-left" class="h-5 w-5 flip-rtl" /></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pg-arrow" aria-label="{{ __('Previous page') }}"><x-icon name="arrow-left" class="h-5 w-5 flip-rtl" /></a>
        @endif

        @php
            $last = $paginator->lastPage(); $cur = $paginator->currentPage();
            $pages = collect([1, $last, $cur - 1, $cur, $cur + 1])->filter(fn ($p) => $p >= 1 && $p <= $last)->unique()->sort()->values();
            $prev = 0;
        @endphp
        @foreach($pages as $p)
            @if($p - $prev > 1)<span class="px-1 text-slate-400" aria-hidden="true">…</span>@endif
            @if($p === $cur)
                <span aria-current="page" class="num grid h-[50px] min-w-[50px] place-items-center rounded-full bg-navy px-3 text-sm font-extrabold text-white dark:bg-brand dark:text-ink">{{ $p }}</span>
            @else
                <a href="{{ $paginator->url($p) }}" class="pg-arrow num text-sm font-bold">{{ $p }}</a>
            @endif
            @php $prev = $p; @endphp
        @endforeach

        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pg-arrow" aria-label="{{ __('Next page') }}"><x-icon name="arrow-right" class="h-5 w-5 flip-rtl" /></a>
        @else
            <span class="pg-arrow is-off" aria-hidden="true"><x-icon name="arrow-right" class="h-5 w-5 flip-rtl" /></span>
        @endif
    </nav>
@endif
