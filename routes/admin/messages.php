<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminMessageController;

# Admin Read Messages
Route::middleware([
    'check.admin.auth',
    'check.admin.read.validation:message_read_request'
])->group(function () {
    Route::get('/read', [AdminMessageController::class, 'read']);
});
