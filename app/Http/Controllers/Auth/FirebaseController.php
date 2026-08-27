<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Infrastructure\Authentication\FirebaseTokenVerifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FirebaseController extends Controller
{
    public function authenticate(Request $request, FirebaseTokenVerifier $verifier): JsonResponse
    {
        $token = $request->validate([
            'id_token' => ['required', 'string'],
        ])['id_token'];

        $claims = $verifier->verify($token);
        $user = User::updateOrCreate(
            ['email' => $claims['email']],
            [
                'name' => $claims['name'] ?: 'Guild adventurer',
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(40)),
            ],
        );

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json(['redirect' => route('dashboard')]);
    }
}
