<?php

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

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All admin operations are grouped here.
| Each module is separated into its own route file.
*/

Route::prefix('admin')->group(function () {
    // Admin authentication routes
    require base_path('routes/admin.php');

    /*
    |--------------------------------------------------------------------------
    | Workspaces
    |--------------------------------------------------------------------------
    */
    Route::prefix('workspaces')->group(function () {
        require base_path('routes/admin/workspaces.php');
    });

    /*
    |--------------------------------------------------------------------------
    | Teams
    |--------------------------------------------------------------------------
    */
    Route::prefix('teams')->group(function () {
        require base_path('routes/admin/teams.php');
    });

    /*
    |--------------------------------------------------------------------------
    | Channels
    |--------------------------------------------------------------------------
    */
    Route::prefix('channels')->group(function () {
        require base_path('routes/admin/channels.php');
    });

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */
    Route::prefix('messages')->group(function () {
        require base_path('routes/admin/messages.php');
    });

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->group(function () {
        require base_path('routes/admin/users.php');
    });

    /*
    |--------------------------------------------------------------------------
    | Impersonation
    |--------------------------------------------------------------------------
    */
    Route::prefix('impersonate')->group(function () {
        require base_path('routes/admin/impersonate.php');
    });
});
