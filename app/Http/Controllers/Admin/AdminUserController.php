<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserReadRequest;
use App\Http\Resources\admin\AdminUserResource;
use App\Http\Resources\admin\AdminUserCollection;
use App\Models\Admin\User;

class AdminUserController extends Controller
{
    public function read(UserReadRequest $request)
    {
        $userId = $request->input('user_id');
        $workspaceId = $request->input('workspace_id');
        $teamId = $request->input('team_id');

        $query = User::orderBy('created_at', 'desc');
        
        $query->when($workspaceId, fn($q) => $q->whereIn('workspace_ids', [$workspaceId]));
        $query->when($teamId, fn($q) => $q->whereIn('team_ids', [$teamId]));
        
        $result = $userId 
            ? $query->findOrFail($userId)
            : $query->paginate($request->input('per_page', 15));

        return $userId 
            ? response()->success('User retrieved successfully!', new AdminUserResource($result))
            : new AdminUserCollection($result);
    }
}
