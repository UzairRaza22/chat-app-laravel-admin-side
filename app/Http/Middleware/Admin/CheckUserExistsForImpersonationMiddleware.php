<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin\User;

class CheckUserExistsForImpersonationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get user_id from query parameters, not route parameters
        $userId = $request->query('user_id') ?? $request->input('user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is required.'
            ], 400);
        }
        
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $request->merge(['impersonate_user' => $user]);

        return $next($request);
    }
}
