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
        $userId = $request->input('user_id');
        
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
            
            $request->attributes->set('user', $user);
        } else {
            // For listing, provide paginated users with filtering
            $query = User::when($request->input('workspace_id'), fn($q, $workspaceId) => $q->whereIn('workspace_ids', [$workspaceId]))
                ->when($request->input('team_id'), fn($q, $teamId) => $q->whereIn('team_ids', [$teamId]))
                ->orderBy('created_at', 'desc');
            
            $users = $query->paginate($request->input('per_page', 10));
            $request->attributes->set('users', $users);
        }

        return $next($request);
    }
}