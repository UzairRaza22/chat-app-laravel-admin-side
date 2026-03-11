<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminWorkspaceController;

// Admin Read Workspaces (all or single)
Route::middleware([
    'check.admin.auth',              // Admin token authentication
    'check.workspace.exists'         // Check if workspace exists when workspace_id provided
])->group(function () {
    Route::get('/read', [AdminWorkspaceController::class, 'read']);
});
