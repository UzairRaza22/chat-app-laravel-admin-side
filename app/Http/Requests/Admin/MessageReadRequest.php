<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MessageReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'channel_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'user_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'message_id.regex' => 'The message id format is invalid.',
            'channel_id.regex' => 'The channel id format is invalid.',
            'user_id.regex' => 'The user id format is invalid.',
        ];
    }
}
