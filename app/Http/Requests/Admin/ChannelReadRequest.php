<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChannelReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'channel_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'team_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'workspace_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'channel_id.regex' => 'The channel id format is invalid.',
            'team_id.regex' => 'The team id format is invalid.',
            'workspace_id.regex' => 'The workspace id format is invalid.',
        ];
    }
}
