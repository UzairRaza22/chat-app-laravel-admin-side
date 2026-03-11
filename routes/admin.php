<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

// Admin authentication routes
Route::prefix('auth')->group(function () {
    // Admin sign-up route
    Route::post('/signup', [AdminAuthController::class, 'signup'])->middleware([
        'check.admin.validation:signup_request',
    Route::post('/signup', [AdminAuthController::class, 'Signup'])->middleware([
        'check.admin.exists',
    ]);

    // Verify admin sign-up route
    Route::post('/verify-signup', [AdminAuthController::class, 'verifySignup'])->middleware([
        'check.admin.validation:verify_signup_request',
    Route::post('/verify-signup', [AdminAuthController::class, 'VerifySignup'])->middleware([
        'check.admin.token:admin_signup_verification_token',
    ]);

    // Admin login route
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware([
        'check.admin.validation:login_request',
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

    // Debug verification endpoint
    Route::post('/debug-verify', function (Illuminate\Http\Request $request) {
        $email = $request->input('email');
        $token = $request->input('token');
        
        if (!$email || !$token) {
            return response()->json([
                'success' => false,
                'message' => 'Email and token are required',
                'received' => [
                    'email' => $email,
                    'token' => $token ? substr($token, 0, 10) . '...' : null
                ]
            ], 400);
        }
        
        // Check if admin exists
        $admin = \App\Models\Admin\Admin::where('email', $email)->first();
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found with this email',
                'email' => $email
            ], 404);
        }
        
        // Check if token exists in database
        $hashedToken = hash('sha256', $token);
        $tokenRecord = \App\Models\Admin\AdminSessionToken::where('token', $hashedToken)
            ->where('token_type', 'admin_signup_verification_token')
            ->first();
            
        if (!$tokenRecord) {
            // Show all verification tokens for debugging
            $allTokens = \App\Models\Admin\AdminSessionToken::where('token_type', 'admin_signup_verification_token')->get();
            
            return response()->json([
                'success' => false,
                'message' => 'Token not found in database',
                'debug' => [
                    'provided_token_preview' => substr($token, 0, 10) . '...',
                    'hashed_token_preview' => substr($hashedToken, 0, 10) . '...',
                    'total_verification_tokens' => $allTokens->count(),
                    'tokens_in_db' => $allTokens->map(function($t) {
                        return [
                            'admin_id' => $t->admin_id,
                            'token_preview' => substr($t->token, 0, 10) . '...',
                            'created_at' => $t->created_at
                        ];
                    })
                ]
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Token found! Verification should work.',
            'debug' => [
                'admin_id' => $admin->_id,
                'admin_email' => $admin->email,
                'admin_is_active' => $admin->is_active,
                'token_admin_id' => $tokenRecord->admin_id,
                'token_created_at' => $tokenRecord->created_at
            ]
        ]);
    });
});