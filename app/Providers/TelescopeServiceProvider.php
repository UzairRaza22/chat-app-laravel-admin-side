<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure Telescope SQLite database file exists when using dedicated telescope connection
        $this->ensureTelescopeDatabaseExists();
        // In local environment, record all requests
        // In production, disable Telescope
        if (!$this->app->environment('local')) {
            Telescope::night();
            return;
        }

        $this->hideSensitiveRequestDetails();

        // Record everything in local development
        Telescope::filter(function (IncomingEntry $entry) {
            return true;  // Record all entries
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
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

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user = null) {
            // Allow access in local environment
            if ($this->app->environment('local')) {
                return true;
            }

            // In production, restrict to specific admin emails
            return $user && in_array($user->email, [
                // Add admin emails here for production access
                // 'admin@example.com',
            ]);
        });
    }

    /**
     * Ensure the Telescope SQLite database file exists so migrations and storage work.
     */
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
