<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

// Admin authentication routes
Route::prefix('auth')->group(function () {
    // Admin sign-up route
    Route::post('/signup', [AdminAuthController::class, 'Signup'])->middleware([
        'check.admin.exists',
    ]);

    // Verify admin sign-up route
    Route::post('/verify-signup', [AdminAuthController::class, 'VerifySignup'])->middleware([
        'check.admin.token:admin_signup_verification_token',
    ]);

    // Admin login route
    Route::post('/login', [AdminAuthController::class, 'Login'])->middleware([
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

    // Test token endpoint for debugging
    Route::get('/test-token', function () {
        return response()->success('Token is valid!', [
            'admin' => request()->user(),
            'timestamp' => now()->toISOString()
        ]);
    })->middleware([
        'check.admin.auth',
    ]);
});