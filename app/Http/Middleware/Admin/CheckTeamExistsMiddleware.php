<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\Team;
use Symfony\Component\HttpFoundation\Response;

class CheckTeamExistsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get team_id from query parameters, not route parameters
        $teamId = $request->query('team_id') ?? $request->input('team_id');

        if ($teamId && !Team::find($teamId)) {
            return response()->json([
                'success' => false,
                'message' => 'Team not found.'
            ], 404);
        }

        return $next($request);
    }
}
