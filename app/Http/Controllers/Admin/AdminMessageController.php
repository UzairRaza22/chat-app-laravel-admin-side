<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageReadRequest;
use App\Http\Resources\admin\AdminMessageResource;
use App\Http\Resources\admin\AdminMessageCollection;

class AdminMessageController extends Controller
{
    public function read(MessageReadRequest $request)
    {
        return $request->input('message_id')
            ? new AdminMessageResource(data_get($request->attributes->all(), 'message'))
            : new AdminMessageCollection(data_get($request->attributes->all(), 'messages'));
    }
}
