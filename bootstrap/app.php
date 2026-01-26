<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // This enables API routing
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ALLOW EXTERNAL PAYMENT GATEWAYS (IntaSend) TO POST TO YOUR WEBHOOK
        // We exclude these paths from CSRF protection because IntaSend cannot provide a CSRF token.
        $middleware->validateCsrfTokens(except: [
            'intasend/webhook',
            'api/intasend/webhook', 
            'api/payment/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();