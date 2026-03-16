<?php

namespace App\Http\Middleware;

use App\Http\Requests\Admin\UserReadRequest;
use App\Http\Requests\Admin\WorkspaceReadRequest;
use App\Http\Requests\Admin\TeamReadRequest;
use App\Http\Requests\Admin\ChannelReadRequest;
use App\Http\Requests\Admin\MessageReadRequest;
use App\Http\Requests\Admin\ImpersonateReadRequest;
use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminReadValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Auto-detect validation type from route path
        $path = $request->path();

        // Only validate read operations
        if (!str_contains($path, '/read')) {
            return $next($request);
        }

        // Determine validation type from the route prefix
        if (str_contains($path, '/admin/users/read')) {
            $validation_type = 'user_read_request';
        } elseif (str_contains($path, '/admin/workspaces/read')) {
            $validation_type = 'workspace_read_request';
        } elseif (str_contains($path, '/admin/teams/read')) {
            $validation_type = 'team_read_request';
        } elseif (str_contains($path, '/admin/channels/read')) {
            $validation_type = 'channel_read_request';
        } elseif (str_contains($path, '/admin/messages/read')) {
            $validation_type = 'message_read_request';
        } elseif (str_contains($path, '/admin/impersonate/read')) {
            $validation_type = 'impersonate_read_request';
        } else {
            return $next($request);
        }

        // User read operations
        if ($validation_type === 'user_read_request') {
            $request->validate(app(UserReadRequest::class)->rules(), app(UserReadRequest::class)->messages());
        }

        // Workspace read operations
        if ($validation_type === 'workspace_read_request') {
            $request->validate(app(WorkspaceReadRequest::class)->rules(), app(WorkspaceReadRequest::class)->messages());
        }

        // Team read operations
        if ($validation_type === 'team_read_request') {
            $request->validate(app(TeamReadRequest::class)->rules(), app(TeamReadRequest::class)->messages());
        }

        // Channel read operations
        if ($validation_type === 'channel_read_request') {
            $request->validate(app(ChannelReadRequest::class)->rules(), app(ChannelReadRequest::class)->messages());
        }

        // Message read operations
        if ($validation_type === 'message_read_request') {
            $request->validate(app(MessageReadRequest::class)->rules(), app(MessageReadRequest::class)->messages());
        }

        // Impersonate read operations
        if ($validation_type === 'impersonate_read_request') {
            if (method_exists(app(ImpersonateReadRequest::class), 'messages')) {
                $request->validate(app(ImpersonateReadRequest::class)->rules(), app(ImpersonateReadRequest::class)->messages());
            } else {
                $request->validate(app(ImpersonateReadRequest::class)->rules());
            }
        }

        return $next($request);
    }
}
