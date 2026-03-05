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
        // Debug: Log what token type we received
        \Log::info('CheckAdminTokenMiddleware called with tokenType: ' . $tokenType . "'");
        
        // For signup verification, check if email exists first
        if ($tokenType === 'admin_signup_verification_token') {
            $email = $request->email;
            $admin = Admin::where('email', $email)->first();
            
            if (!$admin) {
                return response()->json([
                    'message' => 'Email not registered.'
                ], 404);
            }
        }

        // Get token from route, header, or request body
        $token = $request->route('token') ?? 
                 $request->headers->get('authorization') ?? 
                 $request->input('token');
        
        // Remove 'Bearer ' prefix if present
        $token = str_replace('Bearer ', '', $token);
        
        // Debug: Log what token we received
        \Log::info('CheckAdminTokenMiddleware extracted token: ' . $token . "'");
        
        if (!$token) {
            return response()->json([
                'message' => 'Token is required.',
            ], 401);
        }
        
        if (!$tokenType) {
            return response()->json([
                'message' => 'Token type is required.',
            ], 401);
        }
        
        // Use appropriate token model based on token type
        if ($tokenType === 'admin_login_token' || $tokenType === 'admin_signup_verification_token') {
            $tokenRecord = AdminSessionToken::findValidToken($token, $tokenType);
        } elseif ($tokenType === 'admin_forgot_password_token') {
            // Debug: Log the token being searched
            \Log::info('Searching for forgot password token: ' . $token);
            \Log::info('Token type: ' . $tokenType);
            \Log::info('Hashed token: ' . hash('sha256', $token));
            
            // First try with forgot password token type
            $tokenRecord = AdminForgetToken::findValidToken($token, $tokenType);
            
            // If not found, try with login token type (in case user provided login token)
            if (!$tokenRecord) {
                \Log::info('Trying with admin_login_token type');
                $tokenRecord = AdminSessionToken::findValidToken($token, 'admin_login_token');
                if ($tokenRecord) {
                    \Log::info('Found token with admin_login_token type');
                }
            }
            
            // Debug: Log if token was found
            \Log::info('Token record found: ' . ($tokenRecord ? 'Yes' : 'No'));
            if ($tokenRecord) {
                \Log::info('Token record admin_id: ' . $tokenRecord->admin_id);
                \Log::info('Token record type: ' . ($tokenRecord->token_type ?? 'N/A'));
            }
        } else {
            return response()->json([
                'message' => 'Invalid token type.',
            ], 401);
        }
            
        if (!$tokenRecord) {
            return response()->json([
                'message' => 'Invalid or expired token.'
            ], 401);
        }

        // Debug: Check what we have in tokenRecord
        if (!is_object($tokenRecord)) {
            return response()->json([
                'message' => 'Invalid token record format.'
            ], 401);
        }

        // Check if tokenRecord has user_id before accessing it
        if (!isset($tokenRecord->admin_id) || empty($tokenRecord->admin_id)) {
            return response()->json([
                'message' => 'Invalid token format.'
            ], 401);
        }

        $admin = Admin::find((string) $tokenRecord->admin_id);
        
        if (!$admin) {
            return response()->json([
                'message' => 'Admin not found.'
            ], 404);
        }

        $request->merge([
            'token_record' => $tokenRecord,
            'verified_admin' => $admin
        ]);

        // Set admin in user resolver so $request->user() works
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        return $next($request);
    }
}
