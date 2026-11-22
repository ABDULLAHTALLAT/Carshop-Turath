<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureOwner;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocale::class,
            SecurityHeaders::class,
        ]);

        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'owner' => EnsureOwner::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route(auth()->user()?->homeRoute() ?? 'home'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
