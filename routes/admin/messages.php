<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMessageController;

# Admin Read Messages
Route::get('/read', [AdminMessageController::class, 'read']);
