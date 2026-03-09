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

// Load modular route files
Route::prefix('auth')->group(base_path('routes/auth.php'));
Route::prefix('workspaces')->group(base_path('routes/workspaces.php'));
Route::prefix('admin')->group(base_path('routes/admin.php'));

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All admin read operations are grouped here.
| Each module is separated into its own route file.
*/

Route::prefix('admin')->group(function () {

/*
|--------------------------------------------------------------------------
| Workspaces
|--------------------------------------------------------------------------
*/
Route::prefix('workspaces')
->group(base_path('routes/admin/workspaces.php'));

/*
|--------------------------------------------------------------------------
| Teams
|--------------------------------------------------------------------------
*/
Route::prefix('teams')
->group(base_path('routes/admin/teams.php'));

/*
|--------------------------------------------------------------------------
| Channels
|--------------------------------------------------------------------------
*/
Route::prefix('channels')
->group(base_path('routes/admin/channels.php'));

/*
|--------------------------------------------------------------------------
| Messages
|--------------------------------------------------------------------------
*/
Route::prefix('messages')
->group(base_path('routes/admin/messages.php'));

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
Route::prefix('users')
->group(base_path('routes/admin/users.php'));

/*
|--------------------------------------------------------------------------
| Impersonation
|--------------------------------------------------------------------------
*/
Route::prefix('impersonate')
->group(base_path('routes/admin/impersonate.php'));

});
