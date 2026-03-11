<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminChannelResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'description' => $this->description,
            'workspace_id' => $this->workspace_id,
            'team_id' => $this->team_id,
            'creator_id' => $this->creator_id,
            'creator' => $this->creator ? [
                'id' => $this->creator->_id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
            ] : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
