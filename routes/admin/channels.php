<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminChannelController;

// Admin Read Channels (all or single)
Route::middleware([
    'check.admin.auth',              // Admin token authentication
    'check.channel.exists'           // Check if channel exists when channel_id provided
])->group(function () {
    Route::get('/read', [AdminChannelController::class, 'read']);
});
