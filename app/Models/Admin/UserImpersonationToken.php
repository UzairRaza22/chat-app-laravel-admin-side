<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class UserImpersonationToken extends Model
{
    protected $collection = 'user_impersonation_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    /**
     * Generate new impersonation token for a user
     */
    public static function generateForUser($user)
    {
        if (!$user) {
            return null;
        }

        // Delete old impersonation tokens for this user
        self::where('user_id', $user->_id)->delete();

        // Generate token (32 random chars + timestamp)
        $plain_token = Str::random(32) . now()->timestamp;

        // Store hashed token
        $token = self::create([
            'user_id' => $user->_id,
            'token' => hash('sha256', $plain_token),
            'expires_at' => now()->addDays(7), // Token expires in 7 days
        ]);

        return $plain_token;
    }

    /**
     * Find valid impersonation token and get user
     */
    public static function findValidTokenWithUser($plain_token)
    {
        $hashed_token = hash('sha256', $plain_token);

        $token = self::where('token', $hashed_token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$token) {
            return null;
        }

        $user = User::where('_id', $token->user_id)->first();

        return $user;
    }

    /**
     * Find and validate token
     */
    public static function findValid($plain_token)
    {
        return self::where('token', hash('sha256', $plain_token))
            ->where('expires_at', '>', now())
            ->first();
    }
}
