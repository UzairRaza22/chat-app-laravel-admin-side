<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserReadRequest;
use App\Http\Resources\admin\AdminUserResource;
use App\Http\Resources\admin\AdminUserCollection;

class AdminUserController extends Controller
{
    public function read(UserReadRequest $request)
    {
        return $request->input('user_id')
            ? new AdminUserResource(data_get($request->attributes->all(), 'user'))
            : new AdminUserCollection(data_get($request->attributes->all(), 'users'));
    }
}
