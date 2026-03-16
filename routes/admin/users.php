<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;

# Admin Read Users
Route::get('/read', [AdminUserController::class, 'read']);
