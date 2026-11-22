@props(['dark' => false, 'class' => 'h-10'])
<img src="{{ asset($dark ? 'img/logo-white.png' : 'img/logo.png') }}" alt="{{ __('Turath Cars') }}" width="{{ $dark ? 812 : 1597 }}" height="{{ $dark ? 354 : 687 }}" {{ $attributes->merge(['class' => $class.' w-auto']) }}>
