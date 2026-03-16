<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\ResponseServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // API Routes Group with Throttling (30 requests per minute)
            Route::middleware(['api', 'throttle:30,1'])
                ->prefix('api')
                ->name('api.')
                ->group(function () {
                    // Health check endpoint
                    Route::get('/health', function () {
                        return response()->success('Health check passed', [
                            'status' => 'ok',
                            'timestamp' => now()->toISOString(),
                            'version' => '1.0.0',
                            'service' => 'Whistle IT API'
                        ]);
                    });

                    # Public Authentication Routes (No Auth Required)
                    Route::prefix('admin')
                        ->name('admin.')
                        ->group(function () {
                            require base_path('routes/admin/auth.php');
                        });

                    # Protected Admin Routes with Authentication
                    Route::middleware(['check.admin.auth', 'check.admin.read.validation'])
                        ->prefix('admin')
                        ->name('admin.')
                        ->group(function () {
                            Route::prefix('workspaces')->name('workspaces.')->group(function () {
                                require base_path('routes/admin/workspaces.php');
                            });

                            Route::prefix('teams')->name('teams.')->group(function () {
                                require base_path('routes/admin/teams.php');
                            });

                            Route::prefix('channels')->name('channels.')->group(function () {
                                require base_path('routes/admin/channels.php');
                            });

                            Route::prefix('messages')->name('messages.')->group(function () {
                                require base_path('routes/admin/messages.php');
                            });

                            Route::prefix('users')->name('users.')->group(function () {
                                require base_path('routes/admin/users.php');
                            });

                            Route::prefix('impersonate')->name('impersonate.')->group(function () {
                                require base_path('routes/admin/impersonate.php');
                            });
                        });
                });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([

            // Admin authentication middlewares
            'check.admin.validation' => \App\Http\Middleware\CheckAdminValidationMiddleware::class,
            'check.admin.read.validation' => \App\Http\Middleware\CheckAdminReadValidationMiddleware::class,
            'check.admin.token' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class,
            'check.admin.exists' => \App\Http\Middleware\AdminAuth\CheckAdminExistMiddleware::class,
            'check.admin.credentials' => \App\Http\Middleware\AdminAuth\CheckAdminCredentialsMiddleware::class,
            'check.admin.active' => \App\Http\Middleware\AdminAuth\CheckAdminActiveMiddleware::class,
            'check.admin.exists.forgot' => \App\Http\Middleware\AdminAuth\CheckAdminExistForForgotMiddleware::class,

            'check.admin.auth' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class . ':admin_login_token',

            // User impersonation middleware - validates impersonation token
            'impersonate.user' => \App\Http\Middleware\ValidateUserImpersonationToken::class,

        ]);

        $middleware->group('admin.api', [
            'check.admin.auth',
            'check.admin.read.validation',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Throttle exception handler (429 Too Many Requests)
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*')) {
                $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;
                return response()->json([
                    'message' => 'Too many requests. Please try again in ' . $retryAfter . ' seconds.',
                    'status' => 429,
                    'retry_after' => $retryAfter
                ], 429);
            }
        });

        // Delegate exception handling to response macros
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                // HttpResponseException
                if ($e instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
                    return $e->getResponse();
                }

                // Validation Exceptions - use response macro
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->validationError('Validation failed', $e->errors());
                }

                // Let ResponseServiceProvider handle other exceptions
            }
            return null;
        });
    })->create();
