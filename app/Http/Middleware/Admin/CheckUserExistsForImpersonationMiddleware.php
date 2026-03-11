<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\User;
use Symfony\Component\HttpFoundation\Response;

class CheckUserExistsForImpersonationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = data_get($request, 'user_id');
        
        if (!$userId) {
            return response()->validationError('Validation failed', [
                'user_id' => ['The user id field is required for impersonation.']
            ]);
        }
        
        // Validate MongoDB ObjectId format
        if (!preg_match('/^[0-9a-fA-F]{24}$/', $userId)) {
            return response()->validationError('Validation failed', [
                'user_id' => ['The user id format is invalid.']
            ]);
        }
        
        $user = User::find($userId);
        if (!$user) {
            return response()->notFound('User not found for impersonation.');
        }
        
        $request->merge(['validatedUser' => $user]);

        return $next($request);
    }
}
