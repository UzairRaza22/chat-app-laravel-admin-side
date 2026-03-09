<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminWorkspaceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'description' => $this->description,
            'creator_id' => $this->creator?->_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
