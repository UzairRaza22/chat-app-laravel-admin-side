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
        $messageId = data_get($request, 'message_id');
        
        if ($messageId) {
            // Validate MongoDB ObjectId format
            if (!preg_match('/^[0-9a-fA-F]{24}$/', $messageId)) {
                return response()->validationError('Validation failed', [
                    'message_id' => ['The message id format is invalid.']
                ]);
            }
            
            $message = Message::with('user')->find($messageId);
            if (!$message) {
                return response()->notFound('Message not found.');
            }
            
            $request->merge(['validatedMessage' => $message]);
        } else {
            $messages = Message::with('user')->get();
            
            if ($messages->isEmpty()) {
                return response()->notFound('No messages found.');
            }
            
            $request->merge(['validatedMessage' => $messages]);
        }

        return $next($request);
    }
}
