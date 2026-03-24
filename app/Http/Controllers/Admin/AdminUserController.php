<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User;
use App\Http\Resources\admin\AdminUserResource;

class AdminUserController extends Controller
{
    public function read(Request $request)
    {
        $query = User::query();

        User::addFilters($request, $query, true);

        $perPage = data_get($request, 'per_page', 10);
        $users = $query->paginate($perPage);

        return response()->success([
            'users' => AdminUserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }
}
