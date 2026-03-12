<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminWorkspaceController;

# Admin Read Workspaces
Route::middleware([
    'check.admin.auth',
    'check.admin.read.validation:workspace_read_request'
])->group(function () {
    Route::get('/read', [AdminWorkspaceController::class, 'read']);
});
