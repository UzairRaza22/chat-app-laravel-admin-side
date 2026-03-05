<?php

namespace App\Models\Admin;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class AdminForgetToken extends Model
{
    protected $collection = 'forget_tokens';

    protected $fillable = [
        'token_type',
        'admin_id',
        'token',
    ];

    /**
     * Generate new forget password token and delete old tokens for admin
     */
    public static function generate($token_type, $admin){
        // Delete old forget password tokens for this admin
        if ($admin) {
            self::where('admin_id', $admin->_id)
                ->where('token_type', $token_type)
                ->delete();
        }
            
        $plain_token = Str::random(32).now()->timestamp;
        $token = AdminForgetToken::create([
            'token_type'=>$token_type,
            'admin_id'=>$admin ? $admin->_id : null,
            'token'=>hash('sha256', $plain_token),
        ]);

        return $plain_token;
    }

    /**
     * Find valid forget password token
     */
    public static function findValidToken($token, $token_type)
    {
        return self::where('token', hash('sha256', $token))
            ->where('token_type', $token_type)
            ->first();
    }
}
