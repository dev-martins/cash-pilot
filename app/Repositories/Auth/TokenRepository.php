<?php

namespace App\Repositories\Auth;

use Illuminate\Support\Facades\DB;

class TokenRepository
{

    public function revokeExpiredTokens(): void
    {
        DB::table('oauth_access_tokens')
            ->where('expires_at', '<=', now())
            ->where('revoked', 0)
            ->where('created_at', '>=', now()->subDay())
            ->update(['revoked' => 1]);
    }
}
