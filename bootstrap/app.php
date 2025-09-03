<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// USE THE FRAMEWORK MIDDLEWARES:
use Illuminate\Auth\Middleware\Authenticate as AuthenticateMiddleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as RedirectIfAuthenticatedMiddleware;

// Optional: keep your role-check middleware
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/frontend.php',
            __DIR__ . '/../routes/admin.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // point to FRAMEWORK middlewares so guards like :admin work
            'auth'  => AuthenticateMiddleware::class,
            'guest' => RedirectIfAuthenticatedMiddleware::class,

            // optional: your extra role middleware (only if you really need it)
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
