<?php

namespace App\Http\Middleware\AdminAuth;

use App\Models\Admin\Admin;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminExistMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        
        // Check if email already exists in admin table
        $admin = Admin::where('email', $email)->first();
        
        if ($admin) {
            return response()->json([
                'message' => 'Admin already exists'
            ], 409);
        }
        
        // Check if email already exists in user table (cross-table validation)
        $user = User::where('email', $email)->first();
        
        if ($user) {
            return response()->json([
                'message' => 'Email already registered as a user account. Please use a different email.'
            ], 409);
        }

        return $next($request);
    }
}
