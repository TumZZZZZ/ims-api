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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'role'         => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 401 Unauthorized - API only
        $exceptions->renderable(function (
            \Illuminate\Auth\AuthenticationException $e,
            $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'code'    => 401,
                ], 401);
            }

            // non-API → normal behavior
            return redirect()->route('login');
        });

        // 404 Not Found - API vs Web
        $exceptions->renderable(function (
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e,
            $request
        ) {
            // API route not found
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'API route not found',
                    'code'    => 404,
                ], 404);
            }

            // Web route not found
            return redirect()->route('404.page');
        });

        // 405 Method Not Allowed - API only
        $exceptions->renderable(function (
            \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e,
            $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'HTTP method not supported for this route',
                    'code'    => 405,
                ], 405);
            }
        });
    })->create();
