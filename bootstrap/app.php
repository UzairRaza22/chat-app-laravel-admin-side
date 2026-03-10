<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\ResponseServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([

            // Admin authentication middlewares
            'check.admin.validation' => \App\Http\Middleware\CheckAdminValidationMiddleware::class,
            'check.admin.token' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class,
            'check.admin.exists' => \App\Http\Middleware\AdminAuth\CheckAdminExistMiddleware::class,
            'check.admin.credentials' => \App\Http\Middleware\AdminAuth\CheckAdminCredentialsMiddleware::class,
            'check.admin.active' => \App\Http\Middleware\AdminAuth\CheckAdminActiveMiddleware::class,
            'check.admin.exists.forgot' => \App\Http\Middleware\AdminAuth\CheckAdminExistForForgotMiddleware::class,

            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'workspace.access' => \App\Http\Middleware\CheckWorkspaceAccess::class,
            'workspace.ownership' => \App\Http\Middleware\CheckWorkspaceOwnership::class,
            'team.access' => \App\Http\Middleware\CheckTeamAccess::class,
            'team.ownership' => \App\Http\Middleware\CheckTeamOwnership::class,
            'channel.access' => \App\Http\Middleware\CheckChannelAccess::class,
            'channel.ownership' => \App\Http\Middleware\CheckChannelOwnership::class,
            'api.token' => \App\Http\Middleware\ApiTokenAuth::class,
            'workspace.unique.name' => \App\Http\Middleware\Workspace\CheckUniqueWorkspaceNameMiddleware::class,
            'workspace.creator' => \App\Http\Middleware\Workspace\CheckWorkspaceCreatorMiddleware::class,
            'workspace.exists' => \App\Http\Middleware\Workspace\CheckWorkspaceExistsMiddleware::class,
            'workspaces.exist' => \App\Http\Middleware\Workspace\CheckWorkspacesExistMiddleware::class,
        
            // Admin read operations middleware - uses admin login token for authentication
            'admin.auth' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class . ':admin_login_token',
            // Admin resource existence validation middleware
            'admin.workspace.exists' => \App\Http\Middleware\Admin\CheckWorkspaceExistsMiddleware::class,
            'admin.channel.exists' => \App\Http\Middleware\Admin\CheckChannelExistsMiddleware::class,
            'admin.team.exists' => \App\Http\Middleware\Admin\CheckTeamExistsMiddleware::class,
            'admin.message.exists' => \App\Http\Middleware\Admin\CheckMessageExistsMiddleware::class,
            'admin.user.exists.impersonate' => \App\Http\Middleware\Admin\CheckUserExistsForImpersonationMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            // Handle validation exceptions for API routes
            if ($e instanceof \Illuminate\Validation\ValidationException && $request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], $e->status);
            }
            
            return null;
        });
    })->create();
