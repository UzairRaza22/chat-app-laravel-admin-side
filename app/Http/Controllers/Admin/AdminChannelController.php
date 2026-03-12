<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Channel;
use App\Http\Resources\admin\AdminChannelResource;

class AdminChannelController extends Controller
{
    public function read(Request $request)
    {
        $query = Channel::with(['team', 'creator']);

        Channel::addFilters($request, $query, true);

        $perPage = data_get($request, 'per_page', 10);
        $channels = $query->paginate($perPage);

        return response()->success([
            'channels' => AdminChannelResource::collection($channels),
            'pagination' => [
                'total' => $channels->total(),
                'per_page' => $channels->perPage(),
                'current_page' => $channels->currentPage(),
                'last_page' => $channels->lastPage(),
            ]
        ]);
    }
}
