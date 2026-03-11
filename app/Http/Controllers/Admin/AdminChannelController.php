<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChannelReadRequest;
use App\Http\Resources\admin\AdminChannelResource;

class AdminChannelController extends Controller
{
    public function read(ChannelReadRequest $request)
    {
        $channels = data_get($request, 'validatedChannel');

        return response()->success(
            'Channel(s) retrieved successfully!',
            AdminChannelResource::collection(collect($channels))
        );
    }
}
