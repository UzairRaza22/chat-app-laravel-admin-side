<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\Channel;
use Symfony\Component\HttpFoundation\Response;

class CheckChannelExistsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get channel_id from query parameters, not route parameters
        $channelId = $request->query('channel_id') ?? $request->input('channel_id');

        if ($channelId && !Channel::find($channelId)) {
            return response()->json([
                'success' => false,
                'message' => 'Channel not found.'
            ], 404);
        }

        return $next($request);
    }
}
