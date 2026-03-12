<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $collection = 'teams';

    protected $fillable = [
        'workspace_id',
        'name',
        'description',
        'user_ids',
        'creator_id',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
        'user_ids' => [],
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public static function addFilters($request, $query, bool $allowStatus = false)
    {
        if ($id = data_get($request, 'team_id')) {
            $query->where('_id', $id);
        }

        if ($workspaceId = data_get($request, 'workspace_id')) {
            $query->where('workspace_id', $workspaceId);
        }

        if ($allowStatus && ($status = data_get($request, 'status'))) {
            $query->where('is_active', $status == 'active' ? true : false);
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

    public static function add($data)
    {
        return self::create([
            'workspace_id' => data_get($data, 'workspace_id'),
            'name' => data_get($data, 'name'),
            'description' => data_get($data, 'description'),
            'user_ids' => data_get($data, 'user_ids', []),
            'creator_id' => data_get($data, 'creator_id'),
            'is_active' => data_get($data, 'is_active', true),
        ]);
    }

    public static function edit($request)
    {
        $team = data_get($request, 'team');
        $data = [];
        
        if ($request->has('name')) $data['name'] = $request->name;
        if ($request->has('description')) $data['description'] = $request->description;
        if ($request->has('user_ids')) $data['user_ids'] = $request->user_ids;
        if ($request->has('is_active')) $data['is_active'] = $request->is_active;

        $team->update($data);
        return $team;
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
}