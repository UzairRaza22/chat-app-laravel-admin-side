<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\Message;
use Symfony\Component\HttpFoundation\Response;

class CheckMessageExistsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $messageId = $request->input('message_id');
        
        if ($messageId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $messageId)) {
                return response()->validationError('Validation failed', [
                    'message_id' => ['The message id format is invalid.']
                ]);
            }
            
            $message = Message::with(['channel', 'sender'])->find($messageId);
            if (!$message) {
                return response()->notFound('Message not found.');
            }
            
            $request->attributes->set('message', $message);
        } else {
            // For listing, provide paginated messages with filtering
            $query = Message::with(['channel', 'sender'])
                ->when($request->input('channel_id'), fn($q, $channelId) => $q->where('channel_id', $channelId))
                ->when($request->input('user_id'), fn($q, $userId) => $q->where('user_id', $userId))
                ->orderBy('created_at', 'desc');
            
            $messages = $query->paginate($request->input('per_page', 10));
            $request->attributes->set('messages', $messages);
        }

        return $next($request);
    }
}
