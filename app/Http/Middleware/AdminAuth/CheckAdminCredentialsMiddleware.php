<?php

namespace App\Http\Middleware\AdminAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use App\Models\Admin\Admin;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminCredentialsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = strtolower(trim(data_get($request, 'email')));
        $password = data_get($request, 'password');

        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Email and password are required.'
            ], 422);
        }

        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Admin not found.'
            ], 401);
        }

        if (!Hash::check($password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Password incorrect.'
            ], 401);
        }

        $request->merge(['user' => $admin]);
        
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        return $next($request);
    }
}