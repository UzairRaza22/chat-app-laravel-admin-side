<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminSessionToken;
use App\Models\Admin\AdminForgetToken;
use App\Mail\SignupVerificationEmail;
use App\Mail\PasswordResetEmail;
use Illuminate\Support\Facades\Mail;


class AdminAuthController extends Controller
{
    /**
     * Admin signup
     */
    public function signup(Request $request)
    {
        $admin = Admin::add($request);

        $workspace = $admin->createdWorkspaces()->create([
            'name' => data_get($request, 'workspace'),
            'description' => 'Default workspace',
        ]);

        $workspace->members()->attach($admin->_id);


        $token = AdminSessionToken::generate('admin_signup_verification_token', $admin);

        Mail::to($request->email)->send(new SignupVerificationEmail($admin,$token));

        return response()->success([
            'admin' => AdminResource::make($admin)
        ], 'Signup successfull!. Please check your email for verification link.');
    }

    /**
     * Admin verify signup
     */
    public function verifySignup(Request $request)
    {
        $admin = $request->verified_admin;
        $tokenRecord = $request->token_record;

        // Activate the Admin
        $admin->update([
            'is_active' => true
        ]);

        // Delete the verification token
        $tokenRecord->delete();

        return response()->success([
            'admin' => AdminResource::make($admin)
        ], 'Account activated successfully! You can now login.');
    }

    /**
     * Admin login
     */
    public function login(Request $request)
    {
        $admin = $request->user();

        $token = AdminSessionToken::generate('admin_login_token', $admin);
        
        // Store encrypted token in admin model
        $admin->update(['access_token' => hash('sha256', $token)]);

        return response()->success([
            'access_token' => $token,
            'admin' => AdminResource::make($admin)
        ], 'Login successful!');
    }

    /**
     * Admin logout
     */
    public function logout(Request $request)
    {
        $tokenRecord = data_get($request, 'token_record');
        $admin = $request->user();
        
        // Clear access_token from Admin model
        $admin->update(['access_token' => null]);

        $tokenRecord->delete();

        return response()->success(null, 'Logout successful!');
    }

    /**
     * Admin forgot password
     */
    public function forgotPassword(Request $request)
    {
        $admin = $request->admin;

        $token = AdminForgetToken::generate('admin_forgot_password_token', $admin);
        
        Mail::to($admin->email)->send(new PasswordResetEmail($admin, $token));

        return response()->success(null, 'Password reset code sent to your email.');
    }

    /**
     * Admin reset password
     */
    public function resetPassword(Request $request)
    {
        $tokenRecord = data_get($request, 'token_record');

        $admin = Admin::find($tokenRecord->admin_id);

        $admin->update([
            'password' => $request->password
        ]);

        $tokenRecord->delete();

        return response()->success(null, 'Password reset successfully!');
    }
}
