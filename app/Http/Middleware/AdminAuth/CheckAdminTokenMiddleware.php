<?php

namespace App\Http\Middleware\AdminAuth;

use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminSessionToken;
use App\Models\Admin\AdminForgetToken;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $tokenType = null): Response
    {
        // For signup verification, check if email exists first
        if ($tokenType === 'admin_signup_verification_token') {
            $email = $request->email;
            $admin = Admin::where('email', $email)->first();
            
            if (!$admin) {
                return response()->notFound('Email not registered.');
            }
        }

        // Get token from Authorization header, route parameter, or request body
        $token = null;
        
        // First try Authorization header (Bearer token)
        $authHeader = $request->headers->get('authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7); // Remove 'Bearer ' prefix
        }
        
        // If no header token, try route parameter
        if (!$token) {
            $token = $request->route('token');
        }
        
        // If no route token, try request body
        if (!$token) {
            $token = $request->input('token');
        }
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Access token is required. Please provide token in Authorization header as "Bearer {token}".'
            ], 401);
        }
        
        if (!$tokenType) {
            return response()->json([
                'success' => false,
                'message' => 'Token type is required.'
            ], 401);
        }
        
        // Use appropriate token model based on token type
        if ($tokenType === 'admin_login_token' || $tokenType === 'admin_signup_verification_token') {
            $tokenRecord = AdminSessionToken::findValidToken($token, $tokenType);
        } elseif ($tokenType === 'admin_forgot_password_token') {
            // First try with forgot password token type
            $tokenRecord = AdminForgetToken::findValidToken($token, $tokenType);
            
            // If not found, try with login token type (in case user provided login token)
            if (!$tokenRecord) {
                $tokenRecord = AdminSessionToken::findValidToken($token, 'admin_login_token');
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token type: ' . $tokenType
            ], 401);
        }
            
        if (!$tokenRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired access token. Please login again.'
            ], 401);
        }

        // Check if tokenRecord has admin_id before accessing it
        if (!isset($tokenRecord->admin_id) || empty($tokenRecord->admin_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token format.'
            ], 401);
        }

        $admin = Admin::find((string) $tokenRecord->admin_id);
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin account not found.'
            ], 404);
        }

        // Check if admin is active
        if (!$admin->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Admin account is inactive. Please verify your email first.'
            ], 403);
        }

        $request->merge([
            'token_record' => $tokenRecord,
            'verified_admin' => $admin,
            'user' => $admin  // Add this for compatibility
        ]);

        // Set admin in user resolver so $request->user() works
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        return $next($request);
    }
}
