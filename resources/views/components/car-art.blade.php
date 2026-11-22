@props(['type' => 'Sedan', 'color' => '#b9bec7', 'view' => 'side', 'spin' => false, 'shadow' => true, 'label' => null])
{{--
    Original vector car art with a fully TRANSPARENT background.
    The body colour is passed in as a hex value, so every listing gets its own colour.
    No ids / gradients are used so many cars can sit on the same page safely.
--}}
@php
$t = in_array($type, ['Sedan', 'Hatchback', 'SUV', 'Pickup', 'Van', 'Coupe', 'Convertible'], true) ? $type : 'Sedan';
if ($t === 'Convertible') { $t = 'Coupe'; }

$bodies = [
    'Sedan' => ['M26 132C26 122 32 116 44 114L94 107C110 86 134 71 170 69L228 69C256 69 280 84 300 104L352 110C368 112 376 120 376 132L376 144C376 148 373 150 369 150L331 150A31 31 0 0 0 269 150L131 150A31 31 0 0 0 69 150L33 150C29 150 26 148 26 144Z',
        ['M112 106C124 90 142 78 168 75L192 75L192 106Z', 'M200 75L228 75C250 78 268 90 286 106L200 106Z'], [100, 300, 150, 27]],
    'Hatchback' => ['M30 132C30 122 34 116 44 112L70 104C80 84 100 72 126 70L230 70C256 70 280 84 298 104L350 110C366 112 374 120 374 132L374 144C374 148 371 150 367 150L331 150A31 31 0 0 0 269 150L131 150A31 31 0 0 0 69 150L36 150C32 150 30 148 30 144Z',
        ['M84 104C92 88 106 78 128 76L188 76L188 104Z', 'M196 76L228 76C250 78 268 90 284 104L196 104Z'], [100, 300, 150, 27]],
    'SUV' => ['M24 134C24 122 30 114 42 112L56 108C60 80 74 56 104 54L238 54C262 54 280 68 296 98L350 106C368 108 378 118 378 132L378 146C378 150 375 152 371 152L335 152A33 33 0 0 0 269 152L133 152A33 33 0 0 0 67 152L31 152C27 152 24 150 24 146Z',
        ['M70 104C72 82 82 64 106 62L148 62L148 104Z', 'M156 62L200 62L200 104L156 104Z', 'M208 62L238 62C254 62 266 74 280 104L208 104Z'], [100, 302, 152, 29]],
    'Pickup' => ['M22 130L22 112L170 112L170 100C176 78 190 62 216 60L258 60C278 60 292 72 304 94L352 104C370 108 378 118 378 130L378 146C378 150 375 152 371 152L335 152A33 33 0 0 0 269 152L133 152A33 33 0 0 0 67 152L29 152C25 152 22 150 22 146Z',
        ['M184 98C190 80 200 68 218 66L238 66L238 98Z', 'M246 66L258 66C272 68 282 80 292 98L246 98Z'], [100, 302, 152, 29]],
    'Van' => ['M24 138L24 62C24 56 28 52 34 52L270 52C288 52 300 62 312 82L352 108C368 112 378 120 378 134L378 146C378 150 375 152 371 152L335 152A33 33 0 0 0 269 152L133 152A33 33 0 0 0 67 152L30 152C27 152 24 150 24 146Z',
        ['M40 62h56v28h-56Z', 'M104 62h56v28h-56Z', 'M168 62h56v28h-56Z', 'M232 62L266 62C280 62 288 70 298 90L232 90Z'], [100, 302, 152, 29]],
    'Coupe' => ['M26 134C26 126 32 120 44 118L100 110C120 92 148 82 178 82L222 82C246 82 268 92 292 108L352 114C368 116 376 122 376 134L376 146C376 150 373 152 369 152L333 152A31 31 0 0 0 271 152L131 152A31 31 0 0 0 69 152L33 152C29 152 26 150 26 146Z',
        ['M120 108C136 94 156 88 180 88L214 88L214 108Z', 'M222 88C240 90 258 98 276 108L222 108Z'], [100, 302, 152, 27]],
];
[$bodyPath, $windows, [$wx1, $wx2, $wy, $wr]] = $bodies[$t];
$spinClass = $spin ? 'wheel-spin' : '';
$roof = ['Sedan' => 64, 'Hatchback' => 62, 'SUV' => 40, 'Pickup' => 46, 'Van' => 30, 'Coupe' => 76][$t];
$glass = '#d6eefb';
@endphp
@if($view === 'side')
<svg viewBox="0 0 400 182" class="car-art {{ $attributes->get('class') }}" role="img" aria-label="{{ $label ?? $type }}" xmlns="http://www.w3.org/2000/svg">
    @if($shadow)<ellipse cx="200" cy="177" rx="176" ry="5" fill="#0b2447" opacity=".16"/>@endif
    <path d="{{ $bodyPath }}" fill="{{ $color }}"/>
    <path d="{{ $bodyPath }}" fill="#fff" opacity=".10"/>
    {{-- lower shade + shoulder highlight --}}
    <path d="M28 128L374 128L374 144C374 148 371 150 367 150L333 150A31 31 0 0 0 267 150L133 150A31 31 0 0 0 67 150L35 150C31 150 28 148 28 144Z" fill="#000" opacity=".14"/>
    <path d="M52 118L340 114" stroke="#fff" stroke-width="2" stroke-linecap="round" opacity=".38" fill="none"/>
    @foreach($windows as $w)<path d="{{ $w }}" fill="{{ $glass }}" fill-opacity=".9"/><path d="{{ $w }}" fill="#0b2447" opacity=".12"/>@endforeach
    {{-- door cut + handles --}}
    <path d="M197 {{ $t === 'Pickup' ? 100 : 76 }}L197 140" stroke="#000" stroke-width="1.6" opacity=".28" fill="none"/>
    <rect x="170" y="112" width="16" height="3.500" rx="1.700" fill="#000" opacity=".35"/>
    <rect x="212" y="112" width="16" height="3.500" rx="1.700" fill="#000" opacity=".35"/>
    {{-- lights --}}
    <path d="M360 110L374 114C375 118 375 122 373 124L356 122Z" fill="#fff6c9"/>
    <path d="M26 118L36 116L36 128L26 128Z" fill="#e0242b"/>
    {{-- mirror --}}
    <path d="M{{ $t === 'Van' ? 306 : 290 }} 100l10 2-1 6-10-2Z" fill="{{ $color }}"/>
    @if($t === 'SUV')<path d="M104 49L238 49" stroke="#000" stroke-width="3" opacity=".45" stroke-linecap="round"/>@endif
    @foreach([$wx1, $wx2] as $cx)
        <circle cx="{{ $cx }}" cy="{{ $wy }}" r="{{ $wr + 2 }}" fill="#0b1220"/>
        <g class="wheel {{ $spinClass }}">
            <circle cx="{{ $cx }}" cy="{{ $wy }}" r="{{ $wr }}" fill="#1b1d22"/>
            <circle cx="{{ $cx }}" cy="{{ $wy }}" r="{{ round($wr * .58) }}" fill="#c8ced8"/>
            <circle cx="{{ $cx }}" cy="{{ $wy }}" r="{{ round($wr * .46) }}" fill="#98a1af"/>
            @for($i = 0; $i < 5; $i++)
                @php $a = deg2rad($i * 72 - 90); @endphp
                <line x1="{{ $cx }}" y1="{{ $wy }}" x2="{{ round($cx + cos($a) * $wr * .55, 1) }}" y2="{{ round($wy + sin($a) * $wr * .55, 1) }}" stroke="#e8ecf2" stroke-width="3" stroke-linecap="round"/>
            @endfor
            <circle cx="{{ $cx }}" cy="{{ $wy }}" r="4" fill="#4b5563"/>
        </g>
    @endforeach
</svg>
@else
{{-- Front / rear view --}}
@php $rear = $view === 'rear'; @endphp
<svg viewBox="0 0 400 182" class="car-art {{ $attributes->get('class') }}" role="img" aria-label="{{ $label ?? $type }}" xmlns="http://www.w3.org/2000/svg">
    @if($shadow)<ellipse cx="200" cy="177" rx="130" ry="5" fill="#0b2447" opacity=".16"/>@endif
    {{-- cabin --}}
    <path d="M112 108L132 {{ $roof }}Q200 {{ $roof - 8 }} 268 {{ $roof }}L288 108Z" fill="{{ $color }}"/>
    <path d="M112 108L132 {{ $roof }}Q200 {{ $roof - 8 }} 268 {{ $roof }}L288 108Z" fill="#fff" opacity=".10"/>
    <path d="M127 108L141 {{ $roof + 12 }}Q200 {{ $roof + 5 }} 259 {{ $roof + 12 }}L273 108Z" fill="{{ $glass }}" fill-opacity=".9"/>
    <path d="M127 108L141 {{ $roof + 12 }}Q200 {{ $roof + 5 }} 259 {{ $roof + 12 }}L273 108Z" fill="#0b2447" opacity=".14"/>
    {{-- body --}}
    <rect x="92" y="104" width="216" height="56" rx="16" fill="{{ $color }}"/>
    <rect x="92" y="104" width="216" height="56" rx="16" fill="#fff" opacity=".10"/>
    <rect x="92" y="138" width="216" height="22" rx="10" fill="#000" opacity=".16"/>
    <path d="M104 112L296 112" stroke="#fff" stroke-width="2" stroke-linecap="round" opacity=".35"/>
    {{-- mirrors --}}
    <rect x="80" y="{{ $roof + 18 }}" width="16" height="10" rx="4" fill="{{ $color }}"/>
    <rect x="304" y="{{ $roof + 18 }}" width="16" height="10" rx="4" fill="{{ $color }}"/>
    @if(! $rear)
        <path d="M100 116h40q8 0 8 9h-48Z" fill="#fff6c9"/><path d="M300 116h-40q-8 0-8 9h48Z" fill="#fff6c9"/>
        <rect x="156" y="120" width="88" height="18" rx="6" fill="#0b1220" opacity=".8"/>
        <path d="M164 126h72M164 132h72" stroke="#98a1af" stroke-width="1.500" opacity=".7"/>
    @else
        <path d="M98 114h44q6 0 6 10h-50Z" fill="#e0242b"/><path d="M302 114h-44q-6 0-6 10h50Z" fill="#e0242b"/>
        <rect x="150" y="118" width="100" height="6" rx="3" fill="#000" opacity=".25"/>
    @endif
    <rect x="176" y="142" width="48" height="14" rx="3" fill="#f8fafc"/>
    <rect x="98" y="148" width="204" height="9" rx="4.500" fill="#0b1220" opacity=".4"/>
    {{-- tyres --}}
    <rect x="102" y="152" width="30" height="24" rx="6" fill="#1b1d22"/><rect x="268" y="152" width="30" height="24" rx="6" fill="#1b1d22"/>
</svg>
@endif
