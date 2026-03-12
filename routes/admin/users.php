<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;

# Admin Read Users
Route::middleware([
    'check.admin.auth',
    'check.admin.read.validation:user_read_request'
])->group(function () {
    Route::get('/read', [AdminUserController::class, 'read']);
});
