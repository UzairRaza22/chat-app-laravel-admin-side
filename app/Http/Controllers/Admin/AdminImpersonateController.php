<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Admin\UserImpersonationToken;

class AdminImpersonateController extends Controller
{
    /**
     * Generate impersonation token for a user
     *
     * Admin can generate a token to impersonate any user
     */
    public function generateToken(Request $request)
    {
        $userId = $request->input('user_id');

        // Find the user
        $user = User::where('_id', $userId)->first();

        if (!$user) {
            return response()->notFound('User not found');
        }

        // Generate impersonation token for the user
        $impersonationToken = UserImpersonationToken::generateForUser($user);

        return response()->success('User impersonation token generated successfully', [
            'token' => $impersonationToken,
            'user_id' => $user->_id,
            'user_name' => $user->name,
            'expires_in' => '7 days'
        ]);
    }

    /**
     * Stop impersonation
     */
    public function stopImpersonation()
    {
        return response()->success('Impersonation stopped successfully');
    }
}
