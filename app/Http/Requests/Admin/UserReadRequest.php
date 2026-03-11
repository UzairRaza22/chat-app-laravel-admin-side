<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'workspace_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'team_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.regex' => 'The user id format is invalid.',
            'workspace_id.regex' => 'The workspace id format is invalid.',
            'team_id.regex' => 'The team id format is invalid.',
        ];
    }
}
