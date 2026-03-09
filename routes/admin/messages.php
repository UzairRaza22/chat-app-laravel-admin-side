<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMessageController;

// Admin Read Messages (all or single)
Route::middleware([
    'admin.auth',                    // Admin token authentication
    'admin.message.exists'           // Check if message exists when message_id provided
])->group(function () {
    Route::get('/read', [AdminMessageController::class, 'read']);
});
