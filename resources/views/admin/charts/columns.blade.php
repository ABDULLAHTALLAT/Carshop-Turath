@php
    $w = 600; $h = 190; $padL = 26; $padB = 22; $padT = 12;
    $labels = array_keys($series); $values = array_values($series);
    $max = max(1, max($values)); $count = max(1, count($values));
    $slot = ($w - $padL - 6) / $count; $bar = min(34, $slot * .62);
    $step = $count > 14 ? (int) ceil($count / 8) : 1;
@endphp
<svg viewBox="0 0 {{ $w }} {{ $h }}" class="h-auto w-full" role="img" aria-label="{{ $label ?? '' }}">
    @foreach([0, .5, 1] as $g)
        <line class="chart-grid" x1="{{ $padL }}" x2="{{ $w - 6 }}" y1="{{ $padT + ($h - $padT - $padB) * $g }}" y2="{{ $padT + ($h - $padT - $padB) * $g }}" stroke-dasharray="3 5"/>
        <text class="chart-label" x="{{ $padL - 5 }}" y="{{ $padT + ($h - $padT - $padB) * $g + 3 }}" text-anchor="end">{{ round($max * (1 - $g)) }}</text>
    @endforeach
    @foreach($values as $i => $v)
        @php
            $bh = max($v > 0 ? 3 : 0, ($h - $padT - $padB) * ($v / $max));
            $x = $padL + $slot * $i + ($slot - $bar) / 2;
        @endphp
        <rect class="chart-bar" x="{{ round($x, 1) }}" y="{{ round($h - $padB - $bh, 1) }}" width="{{ round($bar, 1) }}" height="{{ round($bh, 1) }}" rx="5" fill="{{ $color ?? '#3a9ad0' }}"><title>{{ $labels[$i] }}: {{ $v }}</title></rect>
        @if($i % $step === 0)<text class="chart-label" x="{{ round($x + $bar / 2, 1) }}" y="{{ $h - 6 }}" text-anchor="middle">{{ $labels[$i] }}</text>@endif
    @endforeach
</svg>
