<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    
    protected $collection = 'users';

    protected $attributes = [
        'is_active' => false,
        'workspace_ids' => [],
        'access_token' => null,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'workspace_ids',
        'access_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    public function createdWorkspaces()
    {
        return $this->hasMany(Workspace::class, 'creator_id', '_id');
    }

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, null, 'user_ids', 'workspace_ids');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, null, 'user_ids', 'team_ids');
    }

    public static function addFilters($request, $query, bool $allowStatus = false)
    {
        if ($id = data_get($request, 'user_id')) {
            $query->where('_id', $id);
        }
        
        if ($workspaceId = data_get($request, 'workspace_id')) {
            $query->whereIn('workspace_ids', [$workspaceId]);
        }

        if ($teamId = data_get($request, 'team_id')) {
            $query->whereIn('team_ids', [$teamId]);
        }

        if ($allowStatus && ($status = data_get($request, 'status'))) {
            $query->where('is_active', $status == 'active' ? true : false);
        }

        if ($search = data_get($request, 'search')) {
            $searchTerm = trim($search);
            $safeSearch = preg_quote($searchTerm);
            $query->where(function ($q) use ($safeSearch) {
                $q->where('name', 'regex', "/{$safeSearch}/i")
                    ->orWhere('email', 'regex', "/{$safeSearch}/i");
            });
        }

        $sortBy = data_get($request, 'sort_by', 'created_at');
        $sortOrder = data_get($request, 'sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
    }

    public static function add($data)
    {
        return self::create([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => data_get($data, 'password'),
            'is_active' => false,
        ]);
    }
}