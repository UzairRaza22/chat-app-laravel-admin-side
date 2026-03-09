<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;

// Admin Read Users (all or single)
Route::middleware([
    'admin.auth',                    // Admin token authentication
    'check.user.exists'              // Check if user exists when user_id provided
])->group(function () {
    Route::get('/read', [AdminUserController::class, 'read']);
});
