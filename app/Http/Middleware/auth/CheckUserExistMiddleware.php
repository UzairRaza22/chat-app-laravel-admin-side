<?php

namespace App\Http\Middleware\auth;

use App\Models\Admin\User;
use App\Models\Admin\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserExistMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        
        // Check if email already exists in user table
        $user = User::where('email', $email)->first();
        
        if ($user) {
            return response()->json([
                'message' => 'User already exists'
            ], 409);
        }
        
        // Check if email already exists in admin table (cross-table validation)
        $admin = Admin::where('email', $email)->first();
        
        if ($admin) {
            return response()->json([
                'message' => 'Email already registered as an admin account. Please use a different email.'
            ], 409);
        }

        return $next($request);
    }
}
