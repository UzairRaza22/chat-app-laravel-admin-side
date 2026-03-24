<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminWorkspaceController;

# Admin Read Workspaces
Route::get('/read', [AdminWorkspaceController::class, 'read']);
