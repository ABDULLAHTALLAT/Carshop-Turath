@php
    $w = 600; $h = 200; $padL = 28; $padB = 24; $padT = 14;
    $labels = array_keys($series); $values = array_values($series);
    $max = max(1, max($values)); $n = max(1, count($values) - 1);
    $pts = [];
    foreach ($values as $i => $v) {
        $pts[] = [round($padL + ($w - $padL - 10) * ($i / $n), 1), round($padT + ($h - $padT - $padB) * (1 - $v / $max), 1)];
    }
    $line = collect($pts)->map(fn ($p, $i) => ($i ? 'L' : 'M').$p[0].' '.$p[1])->implode(' ');
    $area = $line.' L'.end($pts)[0].' '.($h - $padB).' L'.$pts[0][0].' '.($h - $padB).' Z';
@endphp
<svg viewBox="0 0 {{ $w }} {{ $h }}" class="h-auto w-full" role="img" aria-label="{{ $label ?? '' }}">
    <defs><linearGradient id="{{ $id }}" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#6bbce9" stop-opacity=".45"/><stop offset="1" stop-color="#6bbce9" stop-opacity="0"/></linearGradient></defs>
    @foreach([0, .5, 1] as $g)
        <line class="chart-grid" x1="{{ $padL }}" x2="{{ $w - 10 }}" y1="{{ $padT + ($h - $padT - $padB) * $g }}" y2="{{ $padT + ($h - $padT - $padB) * $g }}" stroke-dasharray="3 5"/>
        <text class="chart-label" x="{{ $padL - 6 }}" y="{{ $padT + ($h - $padT - $padB) * $g + 3 }}" text-anchor="end">{{ round($max * (1 - $g)) }}</text>
    @endforeach
    <path d="{{ $area }}" fill="url(#{{ $id }})"/>
    <path class="draw" d="{{ $line }}" fill="none" stroke="#3a9ad0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    @foreach($pts as $i => $p)
        <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="4.5" fill="#fff" stroke="#3a9ad0" stroke-width="2.5"><title>{{ __($labels[$i]) }}: {{ $values[$i] }}</title></circle>
        <text class="chart-label" x="{{ $p[0] }}" y="{{ $h - 6 }}" text-anchor="middle">{{ __($labels[$i]) }}</text>
    @endforeach
</svg>
