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
        $email = data_get($request, 'email');
        $password = data_get($request, 'password');

        $admin = Admin::where('email', $email)->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        return $next($request);
    }
}
