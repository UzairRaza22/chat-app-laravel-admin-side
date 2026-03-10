<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserReadRequest;
use App\Http\Resources\admin\AdminUserResource;

class AdminUserController extends Controller
{
    public function read(UserReadRequest $request)
    {
        $users = data_get($request, 'validatedUser');

        return response()->success(
            'User(s) retrieved successfully!',
            AdminUserResource::collection(collect($users))
        );
    }
}
