<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminWorkspaceController;

// Admin Read Workspaces (all or single with pagination)
Route::middleware([
    'check.admin.auth',              // Admin token authentication only
])->group(function () {
    Route::get('/read', [AdminWorkspaceController::class, 'read']);
});
