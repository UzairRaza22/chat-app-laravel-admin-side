<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\User;
use Symfony\Component\HttpFoundation\Response;

class CheckUserExistsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = data_get($request, 'user_id');
        
        if ($userId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $userId)) {
                return response()->validationError('Validation failed', [
                    'user_id' => ['The user id format is invalid.']
                ]);
            }
            
            $user = User::find($userId);
            if (!$user) {
                return response()->notFound('User not found.');
            }
            
            $request->merge(['validatedUser' => $user]);
        } else {
            $users = User::all();
            
            if ($users->isEmpty()) {
                return response()->notFound('No users found.');
            }
            
            $request->merge(['validatedUser' => $users]);
        }

        return $next($request);
    }
}