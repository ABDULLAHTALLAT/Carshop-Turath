<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }

        Password::defaults(fn () => Password::min(12)->letters()->mixedCase()->numbers());

        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(4)->by($request->ip()));
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
    }
}
