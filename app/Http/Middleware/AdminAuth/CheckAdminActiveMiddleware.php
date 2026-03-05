<?php

namespace App\Http\Middleware\AdminAuth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminActiveMiddleware
{
    /**
     * Handle an incoming request for Admin active status.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the admin user from the request (resolved by your Token Middleware)
        $admin = $request->user();
        
        // 2. Check if admin exists before updating
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found.'
            ], 404);
        }
        
        // 3. Admin Activity Check
        if (!$admin->is_active) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Admin Account Inactive: Please contact the system owner to activate your account.'
            ], 403);
        }
        
        // If all checks pass, proceed to the next request
        return $next($request);
    }
}