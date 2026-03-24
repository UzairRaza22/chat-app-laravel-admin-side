<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminTeamController;

# Admin Read Teams
Route::get('/read', [AdminTeamController::class, 'read']);
