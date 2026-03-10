<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Admin authentication middleware
            'check.admin.token' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class,
            'check.admin.exists' => \App\Http\Middleware\AdminAuth\CheckAdminExistMiddleware::class,
            'check.admin.credentials' => \App\Http\Middleware\AdminAuth\CheckAdminCredentialsMiddleware::class,
            'check.admin.active' => \App\Http\Middleware\AdminAuth\CheckAdminActiveMiddleware::class,
            'check.admin.exists.forgot' => \App\Http\Middleware\AdminAuth\CheckAdminExistForForgotMiddleware::class,

            // Admin read operations middleware - uses admin login token for authentication
            'check.admin.auth' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class . ':admin_login_token',

            // Admin resource existence validation middleware
            'check.workspace.exists' => \App\Http\Middleware\Admin\CheckWorkspaceExistsMiddleware::class,
            'check.channel.exists' => \App\Http\Middleware\Admin\CheckChannelExistsMiddleware::class,
            'check.team.exists' => \App\Http\Middleware\Admin\CheckTeamExistsMiddleware::class,
            'check.message.exists' => \App\Http\Middleware\Admin\CheckMessageExistsMiddleware::class,
            'check.user.exists' => \App\Http\Middleware\Admin\CheckUserExistsMiddleware::class,
            'check.user.exists.impersonate' => \App\Http\Middleware\Admin\CheckUserExistsForImpersonationMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Global Exception Handling for API Routes
        $exceptions->render(function (Throwable $e, Request $request) {
            // Only handle API requests and JSON requests
            if ($request->is('api/*') || $request->expectsJson()) {

                // Handle HttpResponseException (thrown by middleware/requests)
                if ($e instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
                    return $e->getResponse();
                }

                // Handle Validation Exceptions
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $e->errors(),
                    ], 422);
                }

                // Handle Authentication Exceptions
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Authentication required',
                    ], 401);
                }

                // Handle Authorization Exceptions
                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access forbidden',
                    ], 403);
                }

                // Handle Model Not Found Exceptions
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = class_basename($e->getModel());
                    return response()->json([
                        'success' => false,
                        'message' => "{$model} not found",
                    ], 404);
                }

                // Handle Not Found HTTP Exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Endpoint not found',
                    ], 404);
                }

                // Handle Method Not Allowed Exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Method not allowed',
                    ], 405);
                }

                // Handle HTTP Exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage() ?: 'HTTP Error',
                    ], $e->getStatusCode());
                }

                // Handle Database Exceptions
                if ($e instanceof \Illuminate\Database\QueryException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Database operation failed',
                    ], 500);
                }

                // Handle General Exceptions
                if (config('app.debug')) {
                    // In debug mode, show detailed error information
                    return response()->json([
                        'success' => false,
                        'message' => 'Server Error',
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ], 500);
                }

                // In production, show generic error message
                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected error occurred',
                ], 500);
            }

            // For non-API requests, return null to use default Laravel handling
            return null;
        });
    })->create();
