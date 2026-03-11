<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageReadRequest;
use App\Http\Resources\admin\AdminMessageResource;
use App\Http\Resources\admin\AdminMessageCollection;
use App\Models\Admin\Message;

class AdminMessageController extends Controller
{
    public function read(MessageReadRequest $request)
    {
        $messageId = $request->input('message_id');
        $channelId = $request->input('channel_id');
        $userId = $request->input('user_id');

        $query = Message::with(['channel', 'sender'])->orderBy('created_at', 'desc');
        
        $query->when($channelId, fn($q) => $q->where('channel_id', $channelId));
        $query->when($userId, fn($q) => $q->where('sender_id', $userId));
        
        $result = $messageId 
            ? $query->findOrFail($messageId)
            : $query->paginate($request->input('per_page', 15));

        return $messageId 
            ? response()->success('Message retrieved successfully!', new AdminMessageResource($result))
            : new AdminMessageCollection($result);
    }
}
