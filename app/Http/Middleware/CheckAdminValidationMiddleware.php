<?php

namespace App\Http\Middleware;

use App\Http\Requests\AdminAuth\AdminLoginRequest;
use App\Http\Requests\AdminAuth\AdminLogoutRequest;
use App\Http\Requests\AdminAuth\AdminSignupRequest;
use App\Http\Requests\AdminAuth\AdminResetPasswordRequest;
use App\Http\Requests\AdminAuth\AdminForgotPasswordRequest;
use App\Http\Requests\AdminAuth\AdminVerifySignupRequest;
use App\Http\Requests\Workspace\CreateWorkspaceRequest;
use App\Http\Requests\Workspace\UpdateWorkspaceRequest;
use App\Http\Requests\Workspace\AddWorkspaceMemberRequest;
use App\Http\Requests\Workspace\RemoveWorkspaceMemberRequest;
use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $validation_type): Response
    {
        // Auth-related validation requests only
        if ($validation_type === 'logout_request') {
            $request->validate(app(AdminLogoutRequest::class)->rules());
        }
        if ($validation_type === 'signup_request') {
            $request->validate(app(AdminSignupRequest::class)->rules());
        }
        if ($validation_type === 'login_request') {
            $request->validate(app(AdminLoginRequest::class)->rules());
        }
        if ($validation_type === 'verify_signup_request') {
            $request->validate(app(AdminVerifySignupRequest::class)->rules());
        }
        if ($validation_type === 'forgot_password_request') {
            $request->validate(app(AdminForgotPasswordRequest::class)->rules());
        }
        if ($validation_type === 'reset_password_request') {
            $request->validate(app(AdminResetPasswordRequest::class)->rules());
        }
        
        // Workspace validation requests (only for POST/PUT/PATCH)
        if ($validation_type === 'CreateWorkspaceRequest') {
            $request->validate(app(CreateWorkspaceRequest::class)->rules());
        }
        if ($validation_type === 'UpdateWorkspaceRequest') {
            $request->validate(app(UpdateWorkspaceRequest::class)->rules());
        }
        if ($validation_type === 'AddWorkspaceMemberRequest') {
            $request->validate(app(AddWorkspaceMemberRequest::class)->rules());
        }
        if ($validation_type === 'RemoveWorkspaceMemberRequest') {
            $request->validate(app(RemoveWorkspaceMemberRequest::class)->rules());
        }
        
        return $next($request);
    }
}
