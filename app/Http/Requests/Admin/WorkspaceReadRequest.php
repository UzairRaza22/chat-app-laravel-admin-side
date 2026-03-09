<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Workspace;

class WorkspaceReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Admin is authenticated via AdminAuth middleware, so always authorize
        return true;
    }

    public function rules(): array
    {
        return [
            'workspace_id' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('workspace_id')) {
                // Check if it's a valid MongoDB ObjectId format (24 hex characters)
                if (!preg_match('/^[0-9a-fA-F]{24}$/', $this->workspace_id)) {
                    $validator->errors()->add('workspace_id', 'The workspace id must be a valid 24-character MongoDB ObjectId.');
                    return;
                }
                
                $workspace = Workspace::find($this->workspace_id);
                if (!$workspace) {
                    $validator->errors()->add('workspace_id', 'The selected workspace id is invalid.');
                }
            }
        });
    }

    public function validatedWorkspace()
    {
        if ($this->filled('workspace_id')) {
            return Workspace::findOrFail($this->workspace_id);
        }
        
        $workspaces = Workspace::all();
        
        // Check if no workspaces exist and return error response
        if ($workspaces->isEmpty()) {
            $response = response()->json([
                'success' => false,
                'message' => 'No workspaces found.'
            ], 404);
            
            throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
        }
        
        return $workspaces;
    }
}
