<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminTeamController;

// Admin Read Teams (all or single with pagination and filtering)
Route::middleware([
    'check.admin.auth',              // Admin token authentication
    'check.team.exists'              // Check if team exists when team_id provided
])->group(function () {
    Route::get('/read', [AdminTeamController::class, 'read']);
});
