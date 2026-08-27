<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Users\Models\User;
use App\Domain\Users\Rules\ValidGuildName;
use App\Http\Controllers\Controller;
use App\Infrastructure\Authentication\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FirebaseController extends Controller
{
    public function authenticate(Request $request, FirebaseTokenVerifier $verifier): JsonResponse
    {
        $token = $request->validate([
            'id_token' => ['required', 'string'],
        ])['id_token'];

        $claims = $verifier->verify($token);
        $name = $claims['name'] ?: 'Guild adventurer';

        if (! ValidGuildName::passes($name)) {
            throw ValidationException::withMessages([
                'name' => 'Your Google profile name is not allowed. Update it before signing in.',
            ]);
        }

        $user = User::updateOrCreate(
            ['email' => $claims['email']],
            [
                'name' => $name,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(40)),
            ],
        );

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json(['redirect' => route('dashboard')]);
    }
}
