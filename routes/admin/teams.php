<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminTeamController;

// Admin Read Teams (all or single)
Route::middleware([
    'admin.auth',                    // Admin token authentication
    'admin.team.exists'              // Check if team exists when team_id provided
])->group(function () {
    Route::get('/read', [AdminTeamController::class, 'read']);
});
