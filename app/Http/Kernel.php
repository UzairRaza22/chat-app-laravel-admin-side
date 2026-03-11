<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        // Admin Authentication Middleware
        'check.admin.auth' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class . ':admin_login_token',
        'check.admin.token' => \App\Http\Middleware\AdminAuth\CheckAdminTokenMiddleware::class,
        'check.admin.exists' => \App\Http\Middleware\AdminAuth\CheckAdminExistMiddleware::class,
        'check.admin.exists.forgot' => \App\Http\Middleware\AdminAuth\CheckAdminExistForForgotMiddleware::class,
        'check.admin.credentials' => \App\Http\Middleware\AdminAuth\CheckAdminCredentialsMiddleware::class,
        'check.admin.active' => \App\Http\Middleware\AdminAuth\CheckAdminActiveMiddleware::class,
        
        // Admin Resource Validation Middleware
        'check.workspace.exists' => \App\Http\Middleware\Admin\CheckWorkspaceExistsMiddleware::class,
        'check.team.exists' => \App\Http\Middleware\Admin\CheckTeamExistsMiddleware::class,
        'check.channel.exists' => \App\Http\Middleware\Admin\CheckChannelExistsMiddleware::class,
        'check.message.exists' => \App\Http\Middleware\Admin\CheckMessageExistsMiddleware::class,
        'check.user.exists' => \App\Http\Middleware\Admin\CheckUserExistsMiddleware::class,
        'check.user.exists.impersonate' => \App\Http\Middleware\Admin\CheckUserExistsForImpersonationMiddleware::class,
    ];
}
