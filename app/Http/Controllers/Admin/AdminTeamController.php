<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Team;
use App\Http\Resources\admin\AdminTeamResource;

class AdminTeamController extends Controller
{
    public function read(Request $request)
    {
        $query = Team::with(['workspace', 'creator']);

        Team::addFilters($request, $query, true);

        $perPage = data_get($request, 'per_page', 10);
        $teams = $query->paginate($perPage);

        return response()->success([
            'teams' => AdminTeamResource::collection($teams),
            'pagination' => [
                'total' => $teams->total(),
                'per_page' => $teams->perPage(),
                'current_page' => $teams->currentPage(),
                'last_page' => $teams->lastPage(),
            ]
        ]);
    }
}
