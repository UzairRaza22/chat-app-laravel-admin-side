<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminChannelController;

# Admin Read Channels
Route::middleware([
    'check.admin.auth',
    'check.admin.read.validation:channel_read_request'
])->group(function () {
    Route::get('/read', [AdminChannelController::class, 'read']);
});
