<?php

namespace App\Infrastructure\Authentication;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use UnexpectedValueException;

class FirebaseTokenVerifier
{
    public function verify(string $token): array
    {
        $projectId = (string) config('services.firebase.project_id');
        $keys = Cache::remember('firebase.google.public-keys', now()->addHour(), function (): array {
            return Http::timeout(10)
                ->get('https://www.googleapis.com/service_accounts/v1/jwk/securetoken@system.gserviceaccount.com')
                ->throw()
                ->json();
        });

        try {
            $claims = (array) JWT::decode($token, JWK::parseKeySet($keys));
        } catch (BeforeValidException|ExpiredException|SignatureInvalidException|UnexpectedValueException $exception) {
            throw ValidationException::withMessages([
                'id_token' => 'The Google sign-in token is invalid or expired.',
            ]);
        }

        if (
            ($claims['aud'] ?? null) !== $projectId
            || ($claims['iss'] ?? null) !== "https://securetoken.google.com/{$projectId}"
            || empty($claims['sub'])
            || empty($claims['email'])
        ) {
            throw ValidationException::withMessages([
                'id_token' => 'The Google sign-in token was issued for another application.',
            ]);
        }

        return [
            'email' => (string) $claims['email'],
            'name' => (string) ($claims['name'] ?? ''),
        ];
    }
}
