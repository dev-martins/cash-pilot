<?php

namespace App\Services;

use App\Repositories\Auth\TokenRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Validation\ValidationException;

class JWTService
{

    public function __construct(
        protected TokenRepository $tokenRepository,
        protected CacheService $cacheService
    ) {}

    public function validateTokenStructure(string $token): void
    {
        $publicKey = $this->cacheService->get('publicKey');

        if (is_null($publicKey)) {

            $publicKey = file_get_contents(storage_path('/oauth-public.key'));
            $this->cacheService->put('publicKey', $publicKey, env('CACHE_TIME_PUBLIC_KEY'));
        }

        $decoded = get_object_vars(JWT::decode($token, new Key($publicKey, 'RS256')));

        $requiredClaims = ['aud', 'jti', 'iat', 'nbf', 'exp', 'sub', 'scopes'];

        foreach ($requiredClaims as $claim) {
            if (!array_key_exists($claim, $decoded)) {
                throw ValidationException::withMessages([
                    'token' => "Claim obrigatória faltando: {$claim}",
                ]);
            }
        }

        $this->tokenRepository->revokeExpiredTokens();
    }
}
