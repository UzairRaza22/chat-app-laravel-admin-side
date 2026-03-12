<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminImpersonateController;

// Admin Start Impersonation (requires user validation)
Route::middleware([
    'check.admin.auth',
    'check.admin.read.validation:impersonate_read_request'
])->group(function () {
    Route::get('/read', [AdminImpersonateController::class, 'read']);
});

# Admin Stop Impersonation
Route::middleware([
    'check.admin.auth'                  
])->group(function () {
    Route::post('/stop', [AdminImpersonateController::class, 'stop']);
});
