<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageReadRequest;
use App\Http\Resources\admin\AdminMessageResource;

class AdminMessageController extends Controller
{
    public function read(MessageReadRequest $request)
    {
        $messages = $request->validatedMessage();

        return response()->json([
            'success' => true,
            'message' => 'Message(s) retrieved successfully!',
            'data' => $messages instanceof \Illuminate\Database\Eloquent\Collection
                ? AdminMessageResource::collection($messages)
                : AdminMessageResource::make($messages),
        ]);
    }
}
