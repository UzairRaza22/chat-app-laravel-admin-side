<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminImpersonateController;

// Admin Start Impersonation (requires user validation)
Route::middleware([
    'check.admin.auth',                  // Admin token authentication
    'check.user.exists.impersonate'     // Check if user exists for impersonation
])->group(function () {
    Route::get('/read', [AdminImpersonateController::class, 'read']);
});

// Admin Stop Impersonation (only needs admin auth)
Route::middleware([
    'check.admin.auth'                   // Only admin token authentication needed
])->group(function () {
    Route::post('/stop', [AdminImpersonateController::class, 'stop']);
});
