<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ])->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response, Throwable $throwable, Request $request) {
            if (!app()->environment(['local', 'development', 'testing']) && in_array($response->getStatusCode(), [500, 503, 404, 403])) {
                return inertia('ErrorHandling', [
                    'status'  => $response->getStatusCode(),
                ])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            } else if ($response->getStatusCode() === 419) {
                return back()->with([
                    'message' => 'The page expired, please try again'
                ]);
            }

            return $response;
        });
    })->create();
