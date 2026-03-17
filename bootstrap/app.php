<?php

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
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
            'subscribed' => \App\Http\Middleware\EnsureUserSubscribed::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->redirectTo(
            guests: fn ($request) => str_starts_with($request->path(), 'dashboard')
                ? '/auth/login'
                : '/auth/login',
            users: fn ($request) => str_starts_with($request->path(), 'dashboard')
                ? '/dashboard'
                : '/'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
