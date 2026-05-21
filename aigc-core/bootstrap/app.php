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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'api/ai/*', 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->registered(function ($app) {
        // GOOGLE CLOUD OPTIMIZATION: Redirect storage to writable /tmp
        if (env('GAE_INSTANCE') || env('CLOUD_RUN_JOB')) {
            $app->useStoragePath('/tmp/storage');
        }
    })
    ->create();
