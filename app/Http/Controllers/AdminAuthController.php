<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use App\Http\Requests\AdminAuth\AdminSignupRequest;
use App\Http\Requests\AdminAuth\AdminVerifySignupRequest;
use App\Http\Requests\AdminAuth\AdminLoginRequest;
use App\Http\Requests\AdminAuth\AdminLogoutRequest;
use App\Http\Requests\AdminAuth\AdminForgotPasswordRequest;
use App\Http\Requests\AdminAuth\AdminResetPasswordRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminSessionToken;
use App\Models\Admin\AdminForgetToken;
use App\Models\Admin\Workspace;
use App\Mail\Admin\SignupVerificationEmail;
use App\Mail\Admin\PasswordResetEmail;
use Illuminate\Support\Facades\Mail;


class AdminAuthController extends Controller
{
    /**
     * Admin signup
     */
    public function Signup(AdminSignupRequest $request)
    {
        $admin = Admin::add($request);

        $workspace = Workspace::create([
            'name' => data_get($request, 'workspace'),
            'description' => 'Default workspace',
            'creator_id' => (string)$admin->_id,
            'user_ids' => [(string)$admin->_id],
        ]);

        $token = AdminSessionToken::generate('admin_signup_verification_token', $admin);

        Mail::to(data_get($request, 'email'))->send(new SignupVerificationEmail($admin, $token));

        return response()->success(
            'Signup successful! Please check your email for verification link.',
            AdminResource::make($admin)
        );
    }

    /**
     * Admin verify signup
     */
    public function VerifySignup(AdminVerifySignupRequest $request)
    {
        $admin = data_get($request, 'verified_admin');
        $tokenRecord = data_get($request, 'token_record');

        // Activate the Admin
        $admin->update([
            'is_active' => true
        ]);

        // Delete the verification token
        $tokenRecord->delete();

        return response()->success(
            'Account activated successfully! You can now login.',
            AdminResource::make($admin)
        );
    }

    /**
     * Admin login
     */
    public function Login(AdminLoginRequest $request)
    {
        $admin = data_get($request, 'user');

        $token = AdminSessionToken::generate('admin_login_token', $admin);
        
        // Store encrypted token in admin model
        $admin->update(['access_token' => hash('sha256', $token)]);

        return response()->success(
            'Login successful!',
            [
                'access_token' => $token,
                'admin' => AdminResource::make($admin)
            ]
        );
    }

    /**
     * Admin logout
     */
    public function logout(AdminLogoutRequest $request)
    {
        $tokenRecord = data_get($request, 'token_record');
        $admin = data_get($request, 'user');
        
        // Clear access_token from Admin model
        $admin->update(['access_token' => null]);

        $tokenRecord->delete();

        return response()->success('Logout successful!');
    }

    /**
     * Admin forgot password
     */
    public function forgotPassword(AdminForgotPasswordRequest $request)
    {
        $admin = data_get($request, 'admin');

        $token = AdminForgetToken::generate('admin_forgot_password_token', $admin);
        
        Mail::to($admin->email)->send(new PasswordResetEmail($admin, $token));

        return response()->success('Password reset code sent to your email.');
    }

    /**
     * Admin reset password
     */
    public function resetPassword(AdminResetPasswordRequest $request)
    {
        $tokenRecord = data_get($request, 'token_record');

        $admin = Admin::find($tokenRecord->admin_id);

        $admin->update([
            'password' => data_get($request, 'password')
        ]);

        $tokenRecord->delete();

        return response()->success('Password reset successfully!');
    }
}
