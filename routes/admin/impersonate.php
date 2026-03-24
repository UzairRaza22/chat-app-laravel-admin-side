<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminImpersonateController;

/**
 * Admin Impersonation Routes
 *
 * These routes allow admins to generate impersonation tokens for users
 * Frontend can then use these tokens to access data as that user
 */

// Generate impersonation token for a user
Route::get('/read', [AdminImpersonateController::class, 'generateToken']);

// Stop impersonation
Route::post('/stop', [AdminImpersonateController::class, 'stopImpersonation']);

