<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminTeamController;

# Admin Read Teams
Route::middleware([
    'check.admin.auth',
    'check.admin.read.validation:team_read_request'
])->group(function () {
    Route::get('/read', [AdminTeamController::class, 'read']);
});
