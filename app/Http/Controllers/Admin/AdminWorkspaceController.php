<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WorkspaceReadRequest;
use App\Http\Resources\admin\AdminWorkspaceResource;
use App\Http\Resources\admin\AdminWorkspaceCollection;
use App\Models\Admin\Workspace;

class AdminWorkspaceController extends Controller
{
    public function read(WorkspaceReadRequest $request)
    {
        $workspaceId = $request->input('workspace_id');
        
        $query = Workspace::with('creator')->orderBy('created_at', 'desc');
        
        $result = $workspaceId 
            ? $query->findOrFail($workspaceId)
            : $query->paginate($request->input('per_page', 15));

        return $workspaceId 
            ? response()->success('Workspace retrieved successfully!', new AdminWorkspaceResource($result))
            : new AdminWorkspaceCollection($result);
    }
}
