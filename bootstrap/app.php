<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // ->withMiddleware(function (Middleware $middleware) {
    //     $middleware->alias([
    //         'cors' => \Illuminate\Http\Middleware\HandleCors::class, // Middleware CORS có sẵn trong Laravel 11
    //     ]);
    //     $middleware->append(\Illuminate\Http\Middleware\HandleCors::class); // Thêm CORS middleware vào global
    // })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'cors' => \App\Http\Middleware\CorsMiddleware::class, // Thay thế Laravel HandleCors
        ]);
        $middleware->append(\App\Http\Middleware\CorsMiddleware::class); // Thêm vào global middleware
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
