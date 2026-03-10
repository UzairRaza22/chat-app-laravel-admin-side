<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamReadRequest;
use App\Http\Resources\admin\AdminTeamResource;

class AdminTeamController extends Controller
{
    public function read(TeamReadRequest $request)
    {
        $teams = data_get($request, 'validatedTeam');

        return response()->success(
            'Team(s) retrieved successfully!',
            AdminTeamResource::collection(collect($teams))
        );
    }
}
