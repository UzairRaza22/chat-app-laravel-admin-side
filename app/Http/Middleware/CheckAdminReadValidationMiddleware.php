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
    public function handle(Request $request, Closure $next, $validation_type): Response
    {
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
