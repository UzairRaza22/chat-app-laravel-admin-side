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
        return $this->belongsTo(Admin::class, 'creator_id', '_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, null, 'workspace_ids', 'user_ids');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'workspace_id', '_id');
    }

    public static function addFilters($request, $query, bool $allowStatus = false)
    {
        if ($id = data_get($request, 'workspace_id')) {
            $query->where('_id', $id);
        }

        if ($search = data_get($request, 'search')) {
            $searchTerm = trim($search);
            $safeSearch = preg_quote($searchTerm);
            $query->where(function ($q) use ($safeSearch) {
                $q->where('name', 'regex', "/{$safeSearch}/i")
                    ->orWhere('description', 'regex', "/{$safeSearch}/i");
            });
        }

        $sortBy = data_get($request, 'sort_by', 'created_at');
        $sortOrder = data_get($request, 'sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
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