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
        $channelId = data_get($request, 'channel_id');
        
        if ($channelId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $channelId)) {
                return response()->validationError('Validation failed', [
                    'channel_id' => ['The channel id format is invalid.']
                ]);
            }
            
            $channel = Channel::with('creator')->find($channelId);
            if (!$channel) {
                return response()->notFound('Channel not found.');
            }
            
            $request->merge(['validatedChannel' => $channel]);
        } else {
            $channels = Channel::with('creator')->get();
            
            if ($channels->isEmpty()) {
                return response()->notFound('No channels found.');
            }
            
            $request->merge(['validatedChannel' => $channels]);
        }

        return $next($request);
    }
}
