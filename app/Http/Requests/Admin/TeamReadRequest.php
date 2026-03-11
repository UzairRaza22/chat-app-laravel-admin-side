<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'workspace_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'team_id.regex' => 'The team id format is invalid.',
            'workspace_id.regex' => 'The workspace id format is invalid.',
        ];
    }
}
