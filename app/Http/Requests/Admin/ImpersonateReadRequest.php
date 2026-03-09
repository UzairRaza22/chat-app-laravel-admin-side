<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\User;

class ImpersonateReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin is authenticated via AdminAuth middleware, so always authorize
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('user_id')) {
                // Check if it's a valid MongoDB ObjectId format (24 hex characters)
                if (!preg_match('/^[0-9a-fA-F]{24}$/', $this->user_id)) {
                    $validator->errors()->add('user_id', 'The user id must be a valid 24-character MongoDB ObjectId.');
                    return;
                }
                
                $user = User::find($this->user_id);
                if (!$user) {
                    $validator->errors()->add('user_id', 'The selected user id is invalid.');
                }
            }
        });
    }

    public function validatedUser()
    {
        return User::findOrFail($this->user_id);
    }
}
