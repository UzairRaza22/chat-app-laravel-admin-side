<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Workspace;
use App\Http\Resources\admin\AdminWorkspaceResource;


class AdminWorkspaceController extends Controller
{
    public function read(Request $request)
    {
        $query = Workspace::with('creator');

        Workspace::addFilters($request, $query);

        $perPage = data_get($request, 'per_page', 10);
        $workspaces = $query->paginate($perPage);

        return response()->success([
            'workspaces' => AdminWorkspaceResource::collection($workspaces),
            'pagination' => [
                'total' => $workspaces->total(),
                'per_page' => $workspaces->perPage(),
                'current_page' => $workspaces->currentPage(),
                'last_page' => $workspaces->lastPage(),
            ]
        ]);
    }
}
