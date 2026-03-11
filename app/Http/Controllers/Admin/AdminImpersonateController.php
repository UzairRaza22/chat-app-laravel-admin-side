<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImpersonateReadRequest;
use App\Http\Resources\admin\AdminUserResource;
use App\Http\Resources\admin\AdminWorkspaceResource;
use App\Http\Resources\admin\AdminTeamResource;
use App\Http\Resources\admin\AdminChannelResource;
use App\Http\Resources\admin\AdminMessageResource;
use App\Models\Admin\Workspace;
use App\Models\Admin\Team;
use App\Models\Admin\Channel;
use App\Models\Admin\Message;

class AdminImpersonateController extends Controller
{
    public function read(ImpersonateReadRequest $request)
    {
        $user = data_get($request, 'validatedUser');

        // Get all related data for the user
        $relatedData = $this->readUserRelatedData($user);
        
        return response()->success(
            'User impersonation data with related information retrieved successfully!',
            [
                'user' => AdminUserResource::make($user),
                'workspaces' => $relatedData['workspaces'],
                'teams' => $relatedData['teams'],
                'channels' => $relatedData['channels'],
                'messages' => $relatedData['messages'],
                'statistics' => $relatedData['statistics']
            ]
        );
    }

    private function readUserRelatedData($user)
    {
        // Get workspaces where user is a member or creator
        $workspaces = Workspace::where(function($query) use ($user) {
            $query->where('creator_id', $user->_id)
                  ->orWhere('user_ids', $user->_id);
        })->get();

        // Get teams where user is a member
        $teams = Team::where('user_ids', $user->_id)->get();

        // Get channels where user is a member
        $channels = Channel::where('user_ids', $user->_id)->get();

        // Get messages sent by the user (limit to recent 50)
        $messages = Message::where('user_id', $user->_id)
                          ->orderBy('created_at', 'desc')
                          ->limit(50)
                          ->get();

        // Calculate statistics
        $statistics = [
            'total_workspaces' => $workspaces->count(),
            'created_workspaces' => $workspaces->where('creator_id', $user->_id)->count(),
            'joined_workspaces' => $workspaces->where('creator_id', '!=', $user->_id)->count(),
            'total_teams' => $teams->count(),
            'total_channels' => $channels->count(),
            'total_messages' => Message::where('user_id', $user->_id)->count(),
            'recent_messages_shown' => $messages->count(),
        ];

        return [
            'workspaces' => AdminWorkspaceResource::collection($workspaces),
            'teams' => AdminTeamResource::collection($teams),
            'channels' => AdminChannelResource::collection($channels),
            'messages' => AdminMessageResource::collection($messages),
            'statistics' => $statistics
        ];
    }

    public function stop()
    {
        // Clear any impersonation session data
        session()->forget('impersonated_user_id');

        return response()->success('Impersonation stopped successfully!');
    }
}
