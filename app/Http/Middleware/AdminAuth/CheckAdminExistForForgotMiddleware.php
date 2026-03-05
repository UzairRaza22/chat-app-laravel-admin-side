<?php

// namespace App\Http\Middleware\AdminAuth;

// use App\Models\Admin\Admin;
// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

// class CheckAdminExistForForgotMiddleware
// {
//     public function handle(Request $request, Closure $next): Response
//     {
//         $email = $request->email;
        
//         $admin = Admin::where('email', $email)->first();
        
    
//         if (!$admin) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Admin not found.'
//             ], 404);
//         }

//         // Add user to request for controller use
//         $request->merge(['admin' => $admin]);

//         return $next($request);
//     }
// }


namespace App\Http\Middleware\AdminAuth;

use App\Models\Admin\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminExistForForgotMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin) {
            return response()->json([
                'message' => 'Admin not found.'
            ], 404);
        }

        // Add admin to request for controller use
        $request->merge(['admin' => $admin]);

        return $next($request);
    }
}
