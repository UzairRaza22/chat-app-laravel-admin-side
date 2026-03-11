<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use SoftDeletes;
    
    protected $collection = 'workspaces';

    protected $fillable = [
         'id',
        'name',
        'description',
        'creator_id',
        'user_ids',
    ];

    protected $attributes = [
        'user_ids' => [],
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', '_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, null, 'workspace_ids', 'user_ids');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'workspace_id', '_id');
    }

    public static function edit($request)
    {
        $workspace = data_get($request, 'workspace');
        $data = [];
        if ($request->has('name')) $data['name'] = $request->name;
        if ($request->has('description')) $data['description'] = $request->description;

        $workspace->update($data);
        return $workspace;
    }
}