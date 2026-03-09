<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Channel;

class ChannelReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin is authenticated via AdminAuth middleware, so always authorize
        return true;
    }

    public function rules(): array
    {
        return [
            'channel_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('channel_id')) {
                $channel = Channel::find($this->channel_id);
                if (!$channel) {
                    $validator->errors()->add('channel_id', 'The selected channel id is invalid.');
                }
            }
        });
    }

    public function validatedChannel()
    {
        if ($this->filled('channel_id')) {
            return Channel::findOrFail($this->channel_id);
        }
        
        $channels = Channel::all();
        
        // Check if no channels exist and return error response
        if ($channels->isEmpty()) {
            $response = response()->json([
                'success' => false,
                'message' => 'No channels found.'
            ], 404);
            
            throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
        }
        
        return $channels;
    }
}
