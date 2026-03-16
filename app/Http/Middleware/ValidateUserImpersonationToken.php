<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin\UserImpersonationToken;

class ValidateUserImpersonationToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get token from header or query parameter
        $token = $request->headers->get('X-Impersonation-Token') ??
                 $request->input('impersonation_token');

        if (!$token) {
            return response()->unauthorized('Impersonation token is required');
        }

        // Find valid token and get user
        $user = UserImpersonationToken::findValidTokenWithUser($token);

        if (!$user) {
            return response()->unauthorized('Invalid or expired impersonation token');
        }

        // Set the impersonated user in request
        $request->merge([
            'impersonated_user' => $user,
            'impersonated_user_id' => $user->_id
        ]);

        // Set user resolver so $request->user() returns impersonated user
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
