<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{

     # Register any application services.

    public function register(): void
    {
        $this->ensureTelescopeDatabaseExists();

        Telescope::night();

        $this->hideSensitiveRequestDetails();

        # Record everything in all environments
        Telescope::filter(function (IncomingEntry $entry) {
            return true;
        });
    }
      # Prevent sensitive request details from being logged by Telescope.

    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters([
            '_token',
            'password',
            'password_confirmation',
            'token',
            'admin_login_token',
            'impersonation_token',
        ]);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
            'authorization',
            'x-impersonation-token',
        ]);
    }
   #Register the Telescope gate.

    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user = null) {
            return true;
        });
    }

    protected function ensureTelescopeDatabaseExists(): void
    {
        $connection = config('telescope.storage.database.connection');
        if ($connection !== 'telescope') {
            return;
        }
        $path = config('database.connections.telescope.database');
        if ($path && ! file_exists($path)) {
            @touch($path);
        }
    }
}
