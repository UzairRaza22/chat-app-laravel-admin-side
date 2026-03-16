<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes (Public - No Auth Required)
|--------------------------------------------------------------------------
| These routes are public and don't require authentication.
| They handle: signup, login, password reset, etc.
*/

Route::prefix('auth')->group(function () {
    // Admin sign-up route (public)
    Route::post('/signup', [AdminAuthController::class, 'signup'])->middleware([
        'check.admin.validation:signup_request',
        'check.admin.exists',
    ]);

    // Verify admin sign-up route
    Route::post('/verify-signup', [AdminAuthController::class, 'verifySignup'])->middleware([
        'check.admin.validation:verify_signup_request',
        'check.admin.token:admin_signup_verification_token',
    ]);

    // Admin login route (public)
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware([
        'check.admin.validation:login_request',
        'check.admin.credentials',
        'check.admin.active',
    ]);

    // Admin forgot password route (public)
    Route::post('/forgot-password', [AdminAuthController::class, 'forgotPassword'])->middleware([
        'check.admin.exists.forgot',
    ]);

    // Admin reset password (public)
    Route::post('/reset-password', [AdminAuthController::class, 'resetPassword'])->middleware([
        'check.admin.token:admin_forgot_password_token',
    ]);

    // Admin logout route (requires token)
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware([
        'check.admin.token:admin_login_token',
    ]);
});
