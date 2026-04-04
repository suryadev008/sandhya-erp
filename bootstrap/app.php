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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'    => \App\Http\Middleware\EnsureAdmin::class,
            'licensed' => \App\Http\Middleware\CheckSystemLicense::class,
        ]);

        // Sabhi web routes pe system lock check lagao
        $middleware->web(append: [
            \App\Http\Middleware\CheckSystemLicense::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
