<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WorkspaceReadRequest;
use App\Http\Resources\admin\AdminWorkspaceResource;

class AdminWorkspaceController extends Controller
{
    public function read(WorkspaceReadRequest $request)
    {
        $workspaces = data_get($request, 'validatedWorkspace');

        return response()->success(
            'Workspace(s) retrieved successfully!',
            AdminWorkspaceResource::collection(collect($workspaces))
        );
    }
}
