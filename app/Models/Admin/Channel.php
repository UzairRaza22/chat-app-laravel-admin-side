<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $collection = 'channels';

    protected $fillable = [
        'name',
        'description',
        'workspace_id',
        'team_id',
        'type', // public/private/direct
        'creator_id',
        'user_ids', // array of user IDs
        'is_active',
    ];

    protected $attributes = [
        'type' => 'public',
        'user_ids' => [],
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public static function add($data)
    {
        return self::create([
            'name' => data_get($data, 'name'),
            'description' => data_get($data, 'description'),
            'workspace_id' => data_get($data, 'workspace_id'),
            'team_id' => data_get($data, 'team_id'),
            'type' => data_get($data, 'type', 'public'),
            'creator_id' => data_get($data, 'creator_id'),
            'user_ids' => data_get($data, 'user_ids', []),
            'is_active' => data_get($data, 'is_active', true),
        ]);
    }

    public static function edit($request)
    {
        $channel = data_get($request, 'channel');
        $data = [];
        
        if ($request->has('name')) $data['name'] = $request->name;
        if ($request->has('description')) $data['description'] = $request->description;
        if ($request->has('type')) $data['type'] = $request->type;
        if ($request->has('user_ids')) $data['user_ids'] = $request->user_ids;
        if ($request->has('is_active')) $data['is_active'] = $request->is_active;

        $channel->update($data);
        return $channel;
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'creator_id', '_id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id', '_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', '_id');
    }
}