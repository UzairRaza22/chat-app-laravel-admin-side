<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\Workspace;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkspaceExistsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $workspaceId = $request->input('workspace_id');

        if ($workspaceId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $workspaceId)) {
                return response()->validationError('Validation failed', [
                    'workspace_id' => ['The workspace id format is invalid.']
                ]);
            }

            $workspace = Workspace::with('creator')->find($workspaceId);
            if (!$workspace) {
                return response()->notFound('Workspace not found.');
            }

            $request->attributes->set('workspace', $workspace);
        } else {
            // For listing, provide paginated workspaces
            $workspaces = Workspace::with('creator')
                ->orderBy('created_at', 'desc')
                ->paginate($request->input('per_page', 10));
            
            $request->attributes->set('workspaces', $workspaces);
        }

        return $next($request);
    }
}
