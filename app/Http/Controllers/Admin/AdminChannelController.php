<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChannelReadRequest;
use App\Http\Resources\admin\AdminChannelResource;
use App\Http\Resources\admin\AdminChannelCollection;
use App\Models\Admin\Channel;

class AdminChannelController extends Controller
{
    public function read(ChannelReadRequest $request)
    {
        $channelId = $request->input('channel_id');
        $teamId = $request->input('team_id');
        $workspaceId = $request->input('workspace_id');

        $query = Channel::with(['team', 'creator'])->orderBy('created_at', 'desc');
        
        $query->when($teamId, fn($q) => $q->where('team_id', $teamId));
        $query->when($workspaceId, fn($q) => $q->whereHas('team', fn($subQ) => $subQ->where('workspace_id', $workspaceId)));
        
        $result = $channelId 
            ? $query->findOrFail($channelId)
            : $query->paginate($request->input('per_page', 15));

        return $channelId 
            ? response()->success('Channel retrieved successfully!', new AdminChannelResource($result))
            : new AdminChannelCollection($result);
    }
}
