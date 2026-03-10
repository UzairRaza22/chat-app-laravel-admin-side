<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->_id,
            "name"=> $this->name,
            "email"=> $this->email,
            "is_active"=> $this->is_active,
            "updated_at"=> $this->updated_at,
            "created_at"=> $this->created_at,
        ];
    }
}
