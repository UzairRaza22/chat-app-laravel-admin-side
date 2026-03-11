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
            'sender_id' => $this->user_id,
            'sender' => $this->sender ? [
                'id' => $this->sender->_id,
                'name' => $this->sender->name,
                'email' => $this->sender->email,
            ] : null,
            'content' => $this->content,
            'message_type' => $this->message_type,
            'file_path' => $this->file_path,
            'file_name' => $this->file_name,
            'file_mime' => $this->file_mime,
            'parent_message_id' => $this->parent_message_id,
            'is_edited' => $this->is_edited,
            'edited_at' => $this->edited_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
