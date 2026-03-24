<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All admin operations are grouped here.
| All routes have been consolidated into routes/admin.php
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health check endpoint
Route::get('/health', function () {
    return [
        'success' => true,
        'data' => [
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
            'service' => 'Whistle IT API'
        ]
    ];
});

Route::prefix('admin')->group(function () {
    // All admin routes are now in routes/admin.php
    require base_path('routes/admin.php');
});
