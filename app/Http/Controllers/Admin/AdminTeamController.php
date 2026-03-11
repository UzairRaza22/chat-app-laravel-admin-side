<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamReadRequest;
use App\Http\Resources\admin\AdminTeamResource;
use App\Http\Resources\admin\AdminTeamCollection;
use App\Models\Admin\Team;

class AdminTeamController extends Controller
{
    public function read(TeamReadRequest $request)
    {
        $teamId = $request->input('team_id');
        $workspaceId = $request->input('workspace_id');

        $query = Team::with(['workspace', 'creator'])->orderBy('created_at', 'desc');
        
        $query->when($workspaceId, fn($q) => $q->where('workspace_id', $workspaceId));
        
        $result = $teamId 
            ? $query->findOrFail($teamId)
            : $query->paginate($request->input('per_page', 15));

        return $teamId 
            ? response()->success('Team retrieved successfully!', new AdminTeamResource($result))
            : new AdminTeamCollection($result);
    }
}
