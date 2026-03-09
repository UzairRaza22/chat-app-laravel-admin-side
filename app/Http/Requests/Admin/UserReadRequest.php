<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\User;

class UserReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin is authenticated via AdminAuth middleware, so always authorize
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('user_id')) {
                $user = User::find($this->user_id);
                if (!$user) {
                    $validator->errors()->add('user_id', 'The selected user id is invalid.');
                }
            }
        });
    }

    public function validatedUser()
    {
        if ($this->filled('user_id')) {
            return User::findOrFail($this->user_id);
        }
        
        $users = User::all();
        
        // Check if no users exist and return error response
        if ($users->isEmpty()) {
            $response = response()->json([
                'success' => false,
                'message' => 'No users found.'
            ], 404);
            
            throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
        }
        
        return $users;
    }
}
