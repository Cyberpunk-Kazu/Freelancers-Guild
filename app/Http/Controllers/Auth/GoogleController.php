<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();
        $email = $googleUser->getEmail();

        abort_unless($email, 422, 'Google did not provide an email address for this account.');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Guild adventurer',
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(40)),
            ],
        );

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
