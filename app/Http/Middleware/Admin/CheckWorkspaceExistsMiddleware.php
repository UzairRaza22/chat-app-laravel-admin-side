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
        $workspaceId = data_get($request, 'workspace_id');
        
        if ($workspaceId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $workspaceId)) {
                return response()->validationError('Validation failed', [
                    'workspace_id' => ['The workspace id format is invalid.']
                ]);
            }
            
            $workspace = Workspace::find($workspaceId);
            if (!$workspace) {
                return response()->notFound('Workspace not found.');
            }
            
            $request->merge(['validatedWorkspace' => $workspace]);
        } else {
            $workspaces = Workspace::all();
            
            if ($workspaces->isEmpty()) {
                return response()->notFound('No workspaces found.');
            }
            
            $request->merge(['validatedWorkspace' => $workspaces]);
        }

        return $next($request);
    }
}
