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
    ->withBroadcasting(__DIR__.'/../routes/channels.php')
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        
        // Customize guest middleware redirect
        $middleware->redirectGuestsTo(fn () => route('login.form'));
        
        // Customize authenticated user redirect based on role
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if ($user && $user->role === 'admin') {
                return route('admin.dashboard');
            }
            return route('user.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
