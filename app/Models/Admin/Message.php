<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $collection = 'messages';

    protected $fillable = [
        'workspace_id',
        'channel_id',
        'user_id',
        'content',
        'message_type', // text/file/image
        'file_path',
        'file_name',
        'file_mime',
        'parent_message_id',
        'is_edited',
        'edited_at',
    ];

    protected $attributes = [
        'message_type' => 'text',
        'is_edited' => false,
    ];

    protected function casts(): array
    {
        return [
            'is_edited' => 'boolean',
            'edited_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public static function addFilters($request, $query, bool $allowStatus = false)
    {
        if ($id = data_get($request, 'message_id')) {
            $query->where('_id', $id);
        }
        
        if ($channelId = data_get($request, 'channel_id')) {
            $query->where('channel_id', $channelId);
        }

        if ($userId = data_get($request, 'user_id')) {
            $query->where('user_id', $userId);
        }

        if ($search = data_get($request, 'search')) {
            $searchTerm = trim($search);
            $safeSearch = preg_quote($searchTerm);
            $query->where(function ($q) use ($safeSearch) {
                $q->where('content', 'regex', "/{$safeSearch}/i")
                    ->orWhere('file_name', 'regex', "/{$safeSearch}/i");
            });
        }

        $sortBy = data_get($request, 'sort_by', 'created_at');
        $sortOrder = data_get($request, 'sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
    }

    public static function add($data)
    {
        return self::create([
            'workspace_id' => data_get($data, 'workspace_id'),
            'channel_id' => data_get($data, 'channel_id'),
            'user_id' => data_get($data, 'user_id'),
            'content' => data_get($data, 'content'),
            'message_type' => data_get($data, 'message_type', 'text'),
            'file_path' => data_get($data, 'file_path'),
            'file_name' => data_get($data, 'file_name'),
            'file_mime' => data_get($data, 'file_mime'),
            'parent_message_id' => data_get($data, 'parent_message_id'),
        ]);
    }

    public static function edit($request)
    {
        $message = data_get($request, 'message');
        $data = [];
        
        if ($request->has('content')) {
            $data['content'] = $request->content;
            $data['is_edited'] = true;
            $data['edited_at'] = now();
        }
        
        if ($request->has('file_path')) {
            $data['file_path'] = $request->file_path;
            $data['file_name'] = $request->file_name;
            $data['file_mime'] = $request->file_mime;
        }

        $message->update($data);
        return $message;
    }

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id', '_id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id', '_id');
    }
}