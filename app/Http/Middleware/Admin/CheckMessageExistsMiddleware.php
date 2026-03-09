<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\Message;
use Symfony\Component\HttpFoundation\Response;

class CheckMessageExistsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get message_id from query parameters, not route parameters
        $messageId = $request->query('message_id') ?? $request->input('message_id');

        if ($messageId && !Message::find($messageId)) {
            return response()->json([
                'success' => false,
                'message' => 'Message not found.'
            ], 404);
        }

        return $next($request);
    }
}
