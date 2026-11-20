<?php

return [

    'name' => env('APP_NAME', 'Turath Cars'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    // Redirect every generated URL to https and send HSTS (enable in production)
    'force_https' => (bool) env('APP_FORCE_HTTPS', false),

    'timezone' => 'Africa/Cairo',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => 'file',
    ],

];
