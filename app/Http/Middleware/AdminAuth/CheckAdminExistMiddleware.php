<?php

namespace App\Http\Middleware\AdminAuth;

use App\Models\Admin\Admin;
use App\Models\Admin\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminExistMiddleware
{
    public function handle(Request $request, Closure $next): Response
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
}