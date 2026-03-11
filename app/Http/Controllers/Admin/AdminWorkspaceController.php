<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WorkspaceReadRequest;
use App\Http\Resources\admin\AdminWorkspaceResource;
use App\Http\Resources\admin\AdminWorkspaceCollection;

class AdminWorkspaceController extends Controller
{
    public function read(WorkspaceReadRequest $request)
    {
        return $request->input('workspace_id')
            ? new AdminWorkspaceResource(data_get($request->attributes->all(), 'workspace'))
            : new AdminWorkspaceCollection(data_get($request->attributes->all(), 'workspaces'));
    }
}
