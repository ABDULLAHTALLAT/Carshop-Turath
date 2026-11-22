<?php

use App\Support\EgyptData;

if (! function_exists('egp')) {
    /** Format a price in Egyptian pounds, localised */
    function egp(int|float|null $amount): string
    {
        $n = number_format((float) $amount);

        return app()->getLocale() === 'ar' ? "{$n} ج.م" : "EGP {$n}";
    }
}

if (! function_exists('t')) {
    /** Translate a catalogue value (brand, colour, governorate...) */
    function t(?string $value): string
    {
        return $value === null || $value === '' ? '' : __($value);
    }
}

if (! function_exists('is_rtl')) {
    function is_rtl(): bool
    {
        return app()->getLocale() === 'ar';
    }
}

if (! function_exists('color_hex')) {
    function color_hex(?string $name): string
    {
        return EgyptData::COLORS[$name][0] ?? '#b9bec7';
    }
}
