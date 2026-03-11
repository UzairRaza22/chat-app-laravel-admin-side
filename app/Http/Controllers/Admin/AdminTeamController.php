<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamReadRequest;
use App\Http\Resources\admin\AdminTeamResource;
use App\Http\Resources\admin\AdminTeamCollection;

class AdminTeamController extends Controller
{
    public function read(TeamReadRequest $request)
    {
        return $request->input('team_id')
            ? new AdminTeamResource(data_get($request->attributes->all(), 'team'))
            : new AdminTeamCollection(data_get($request->attributes->all(), 'teams'));
    }
}
