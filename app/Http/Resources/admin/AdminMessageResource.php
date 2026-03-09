<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminMessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'channel_id' => $this->channel_id,
            'user_id' => $this->user?->_id,
            'content' => $this->content,
            'attachments' => $this->attachments,
            'parent_message_id' => $this->parent_message_id,
            'is_edited' => $this->is_edited,
            'edited_at' => $this->edited_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
