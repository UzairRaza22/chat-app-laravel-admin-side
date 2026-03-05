<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class AdminSessionToken extends Model
{
    protected $collection = 'session_tokens';

    protected $fillable = [
        'token_type',
        'admin_id',
        'token',
    ];

    /**
     * Generate new session token and delete old tokens for admin
     */
    public static function generate($token_type, $admin){
        // Delete old session tokens for this admin
        if ($admin) {
            self::where('admin_id', $admin->_id)
                ->where('token_type', $token_type)
                ->delete();
        }
            
        $plain_token = Str::random(32).now()->timestamp;
        $token = AdminSessionToken::create([
            'token_type'=>$token_type,
            'admin_id'=>$admin ? $admin->_id : null,
            'token'=>hash('sha256', $plain_token),
        ]);

        return $plain_token;
    }

    /**
     * Find valid session token
     */
    public static function findValidToken($token, $token_type)
    {
        return self::where('token', hash('sha256', $token))
            ->where('token_type', $token_type)
            ->first();
    }
}
