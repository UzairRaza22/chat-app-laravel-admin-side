<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\AdminChannelController;
use App\Http\Controllers\Admin\AdminImpersonateController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWorkspaceController;

// Admin authentication routes
Route::prefix('auth')->group(function () {
    // Admin sign-up route
    Route::post('/signup', [AdminAuthController::class, 'signup'])->middleware([
        'check.admin.validation:signup_request',
        'check.admin.exists',
    ]);

    // Verify admin sign-up route
    Route::post('/verify-signup', [AdminAuthController::class, 'verifySignup'])->middleware([
        'check.admin.validation:verify_signup_request',
        'check.admin.token:admin_signup_verification_token',
    ]);

    // Admin login route
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware([
        'check.admin.validation:login_request',
        'check.admin.credentials',
        'check.admin.active',
    ]);

    // Admin forgot password route
    Route::post('/forgot-password', [AdminAuthController::class, 'forgotPassword'])->middleware([
        'check.admin.exists.forgot',
    ]);

    // Admin reset password
    Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->middleware([
        'check.admin.token:admin_forgot_password_token',
    ]);

    // Admin logout route
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware([
        'check.admin.token:admin_login_token',
    ]);
});

// Admin Channels routes
Route::prefix('channels')->middleware([
    'check.admin.auth',              // Admin token authentication
    'check.channel.exists'           // Check if channel exists when channel_id provided
])->group(function () {
    Route::get('/read', [AdminChannelController::class, 'read']);
});

// Admin Impersonation routes
Route::prefix('impersonate')->group(function () {
    // Admin Start Impersonation (requires user validation)
    Route::middleware([
        'check.admin.auth',                  // Admin token authentication
        'check.user.exists.impersonate'     // Check if user exists for impersonation
    ])->group(function () {
        Route::get('/read', [AdminImpersonateController::class, 'read']);
    });

    // Admin Stop Impersonation (only needs admin auth)
    Route::middleware([
        'check.admin.auth'                   // Only admin token authentication needed
    ])->group(function () {
        Route::post('/stop', [AdminImpersonateController::class, 'stop']);
    });
});

// Admin Messages routes
Route::prefix('messages')->middleware([
    'check.admin.auth',              // Admin token authentication
    'check.message.exists'           // Check if message exists when message_id provided
])->group(function () {
    Route::get('/read', [AdminMessageController::class, 'read']);
});

// Admin Teams routes
Route::prefix('teams')->middleware([
    'check.admin.auth',              // Admin token authentication
    'check.team.exists'              // Check if team exists when team_id provided
])->group(function () {
    Route::get('/read', [AdminTeamController::class, 'read']);
});

// Admin Users routes
Route::prefix('users')->middleware([
    'check.admin.auth',              // Admin token authentication
    'check.user.exists'              // Check if user exists when user_id provided
])->group(function () {
    Route::get('/read', [AdminUserController::class, 'read']);
});

// Admin Workspaces routes
Route::prefix('workspaces')->middleware([
    'check.admin.auth',              // Admin token authentication
    'check.workspace.exists'         // Check if workspace exists when workspace_id provided
])->group(function () {
    Route::get('/read', [AdminWorkspaceController::class, 'read']);
});

