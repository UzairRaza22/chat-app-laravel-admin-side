<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminChannelController;

# Admin Read Channels
Route::get('/read', [AdminChannelController::class, 'read']);
