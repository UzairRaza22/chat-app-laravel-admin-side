<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;

// Admin Read Users (all or single with pagination and filtering)
Route::middleware([
    'check.admin.auth',              // Admin token authentication only
])->group(function () {
    Route::get('/read', [AdminUserController::class, 'read']);
});
