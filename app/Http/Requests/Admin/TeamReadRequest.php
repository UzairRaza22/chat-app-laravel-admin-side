<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Team;

class TeamReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin is authenticated via AdminAuth middleware, so always authorize
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => ['nullable', 'string', 'regex:/^[0-9a-fA-F]{24}$/'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('team_id')) {
                $team = Team::find($this->team_id);
                if (!$team) {
                    $validator->errors()->add('team_id', 'The selected team id is invalid.');
                }
            }
        });
    }

    public function validatedTeam()
    {
        if ($this->filled('team_id')) {
            return Team::findOrFail($this->team_id);
        }
        
        $teams = Team::all();
        
        // Check if no teams exist and return error response
        if ($teams->isEmpty()) {
            $response = response()->json([
                'success' => false,
                'message' => 'No teams found.'
            ], 404);
            
            throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
        }
        
        return $teams;
    }
}
