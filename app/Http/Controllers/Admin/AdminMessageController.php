<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageReadRequest;
use App\Http\Resources\admin\AdminMessageResource;

class AdminMessageController extends Controller
{
    public function read(MessageReadRequest $request)
    {
        $messages = data_get($request, 'validatedMessage');

        return response()->success(
            'Message(s) retrieved successfully!',
            AdminMessageResource::collection(collect($messages))
        );
    }
}
