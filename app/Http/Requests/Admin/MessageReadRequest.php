<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Message;

class MessageReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin is authenticated via AdminAuth middleware, so always authorize
        return true;
    }

    public function rules(): array
    {
        return [
            'message_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('message_id')) {
                $message = Message::find($this->message_id);
                if (!$message) {
                    $validator->errors()->add('message_id', 'The selected message id is invalid.');
                }
            }
        });
    }

    public function validatedMessage()
    {
        if ($this->filled('message_id')) {
            return Message::findOrFail($this->message_id);
        }
        
        $messages = Message::all();
        
        // Check if no messages exist and return error response
        if ($messages->isEmpty()) {
            $response = response()->json([
                'success' => false,
                'message' => 'No messages found.'
            ], 404);
            
            throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
        }
        
        return $messages;
    }
}
