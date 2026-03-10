<?php

namespace App\Http\Middleware\AdminAuth;

use App\Models\Admin\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminExistForForgotMiddleware
{
    public function handle(Request $request, Closure $next): Response
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
}
