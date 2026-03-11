<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\Team;
use Symfony\Component\HttpFoundation\Response;

class CheckTeamExistsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teamId = $request->input('team_id');
        
        if ($teamId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $teamId)) {
                return response()->validationError('Validation failed', [
                    'team_id' => ['The team id format is invalid.']
                ]);
            }
            
            $team = Team::with(['workspace', 'creator'])->find($teamId);
            if (!$team) {
                return response()->notFound('Team not found.');
            }
            
            $request->attributes->set('team', $team);
        } else {
            // For listing, provide paginated teams with filtering
            $query = Team::with(['workspace', 'creator'])
                ->when($request->input('workspace_id'), fn($q, $workspaceId) => $q->where('workspace_id', $workspaceId))
                ->orderBy('created_at', 'desc');
            
            $teams = $query->paginate($request->input('per_page', 10));
            $request->attributes->set('teams', $teams);
        }

        return $next($request);
    }
}
