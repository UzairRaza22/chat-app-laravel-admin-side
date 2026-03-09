<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminTeamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'description' => $this->description,
            'workspace_id' => $this->workspace_id,
            'creator_id' => $this->creator?->_id,
            'member_ids' => $this->member_ids,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
