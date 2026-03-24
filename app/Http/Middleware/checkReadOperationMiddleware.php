<?php

namespace App\Http\Middleware;

use App\Models\Admin\Admin;
use App\Models\Admin\User;
use App\Models\Admin\AdminSessionToken;
use App\Models\Admin\AdminForgetToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Symfony\Component\HttpFoundation\Response;

class checkReadOperationMiddleware
{
    /**
     * Handle an incoming request for various read operations.
     * Combines functionality from CheckAdminActive, CheckAdminCredentials, 
     * CheckAdminExist, CheckAdminExistForForgot, and CheckAdminToken middlewares.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the operation type from request or route
        $operation = $request->input('operation', $request->route('operation'));
        
        switch ($operation) {
            case 'check_admin_active':
                return $this->checkAdminActive($request, $next);
                
            case 'check_admin_credentials':
                return $this->checkAdminCredentials($request, $next);
                
            case 'check_admin_exists':
                return $this->checkAdminExists($request, $next);
                
            case 'check_admin_exists_forgot':
                return $this->checkAdminExistsForForgot($request, $next);
                
            case 'check_admin_token':
                return $this->checkAdminToken($request, $next);
                
            default:
                return $next($request);
        }
    }
    
    /**
     * Check if admin is active
     */
    private function checkAdminActive(Request $request, Closure $next): Response
    {
        // 1. Get the admin user from the request (resolved by your Token Middleware)
        $admin = $request->user();
        
        // 2. Check if admin exists before updating
        if (!$admin) {
            return response()->notFound('Admin not found.');
        }
        
        // 3. Admin Activity Check
        if (!$admin->is_active) {
            return response()->forbidden('Admin Account Inactive: Please contact system owner to activate your account.');
        }
        
        // If all checks pass, proceed to the next request
        return $next($request);
    }
    
    /**
     * Check admin credentials
     */
    private function checkAdminCredentials(Request $request, Closure $next): Response
    {
        $email = strtolower(trim(data_get($request, 'email')));
        $password = data_get($request, 'password');

        $admin = Admin::whereRaw(['email' => ['$regex' => '^' . preg_quote($email) . '$', '$options' => 'i']])->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            return response()->unauthorized('Invalid credentials');
        }

        $request->merge(['user' => $admin]);
        
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        return $next($request);
    }
    
    /**
     * Check if admin exists (for signup)
     */
    private function checkAdminExists(Request $request, Closure $next): Response
    {
        $email = strtolower(trim($request->email));
        
        // Check if email already exists in admin table (case-insensitive)
        $admin = Admin::whereRaw(['email' => ['$regex' => '^' . preg_quote($email) . '$', '$options' => 'i']])->first();
        
        if ($admin) {
            return response()->error('Admin already exists', 409);
        }
        
        // Check if email already exists in user table (cross-table validation, case-insensitive)
        $user = User::whereRaw(['email' => ['$regex' => '^' . preg_quote($email) . '$', '$options' => 'i']])->first();
        
        if ($user) {
            return response()->error('Email already registered as a user account. Please use a different email.', 409);
        }

        return $next($request);
    }
    
    /**
     * Check if admin exists (for forgot password)
     */
    private function checkAdminExistsForForgot(Request $request, Closure $next): Response
    {
        $email = strtolower(trim($request->email));
        
        $admin = Admin::whereRaw(['email' => ['$regex' => '^' . preg_quote($email) . '$', '$options' => 'i']])->first();
        
        if (!$admin) {
            return response()->notFound('Admin not found.');
        }

        // Add admin to request for controller use
        $request->merge(['admin' => $admin]);

        return $next($request);
    }
    
    /**
     * Check admin token
     */
    private function checkAdminToken(Request $request, Closure $next): Response
    {
        // Get token type from request parameter
        $tokenType = $request->input('token_type');
        
        // For signup verification, check if email exists first
        if ($tokenType === 'admin_signup_verification_token') {
            $email = $request->email;
            $admin = Admin::where('email', $email)->first();
            
            if (!$admin) {
                return response()->notFound('Email not registered.');
            }
        }
        
        // Get token from route, header, or request body
        $token = $request->route('token') ?? 
                 $request->headers->get('authorization') ?? 
                 $request->input('token');
        
        // Remove 'Bearer ' prefix if present
        $token = str_replace('Bearer ', '', $token);
        
        if (!$token) {
            return response()->unauthorized('Token is required.');
        }
        
        if (!$tokenType) {
            return response()->unauthorized('Token type is required.');
        }
        
        // Find token record based on type
        if ($tokenType === 'admin_login_token' || $tokenType === 'admin_signup_verification_token') {
            $tokenRecord = AdminSessionToken::findValidToken($token, $tokenType);
        } elseif ($tokenType === 'admin_forgot_password_token') {
            $tokenRecord = AdminForgetToken::findValidToken($token, $tokenType);
            
            if (!$tokenRecord) {
                $tokenRecord = AdminSessionToken::findValidToken($token, 'admin_login_token');
            }
        } else {
            return response()->unauthorized('Invalid token type.');
        }
        
        if (!$tokenRecord) {
            return response()->unauthorized('Invalid or expired token.');
        }
        
        // Find admin by token record's admin_id
        $admin = Admin::find($tokenRecord->admin_id);
        if (!$admin) {
            return response()->notFound('Admin not found.');
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
