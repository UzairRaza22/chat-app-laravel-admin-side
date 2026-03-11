<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\Channel;
use Symfony\Component\HttpFoundation\Response;

class CheckChannelExistsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $channelId = $request->input('channel_id');
        
        if ($channelId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $channelId)) {
                return response()->validationError('Validation failed', [
                    'channel_id' => ['The channel id format is invalid.']
                ]);
            }
            
            $channel = Channel::with(['team', 'creator'])->find($channelId);
            if (!$channel) {
                return response()->notFound('Channel not found.');
            }
            
            $request->attributes->set('channel', $channel);
        } else {
            // For listing, provide paginated channels with filtering
            $query = Channel::with(['team', 'creator'])
                ->when($request->input('team_id'), fn($q, $teamId) => $q->where('team_id', $teamId))
                ->when($request->input('workspace_id'), fn($q, $workspaceId) => 
                    $q->whereHas('team', fn($subQ) => $subQ->where('workspace_id', $workspaceId))
                )
                ->orderBy('created_at', 'desc');
            
            $channels = $query->paginate($request->input('per_page', 10));
            $request->attributes->set('channels', $channels);
        }

        return $next($request);
    }
}
