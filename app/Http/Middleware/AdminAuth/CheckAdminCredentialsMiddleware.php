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

        $admin = Admin::whereRaw(['email' => ['$regex' => '^' . preg_quote($email) . '$', '$options' => 'i']])->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            return response()->unauthorized('Invalid credentials');
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