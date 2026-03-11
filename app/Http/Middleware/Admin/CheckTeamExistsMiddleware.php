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
        $teamId = data_get($request, 'team_id');
        
        if ($teamId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $teamId)) {
                return response()->validationError('Validation failed', [
                    'team_id' => ['The team id format is invalid.']
                ]);
            }
            
            $team = Team::find($teamId);
            if (!$team) {
                return response()->notFound('Team not found.');
            }
            
            $request->merge(['validatedTeam' => $team]);
        } else {
            $teams = Team::all();
            
            if ($teams->isEmpty()) {
                return response()->notFound('No teams found.');
            }
            
            $request->merge(['validatedTeam' => $teams]);
        }

        return $next($request);
    }
}
