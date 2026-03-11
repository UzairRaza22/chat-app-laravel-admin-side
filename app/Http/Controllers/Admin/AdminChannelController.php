<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChannelReadRequest;
use App\Http\Resources\admin\AdminChannelResource;
use App\Http\Resources\admin\AdminChannelCollection;

class AdminChannelController extends Controller
{
    public function read(ChannelReadRequest $request)
    {
        return $request->input('channel_id')
            ? new AdminChannelResource(data_get($request->attributes->all(), 'channel'))
            : new AdminChannelCollection(data_get($request->attributes->all(), 'channels'));
    }
}
