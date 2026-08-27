<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Users\Models\User;
use App\Domain\Users\Rules\ValidGuildName;
use App\Http\Controllers\Controller;
use App\Domain\Users\Models\EmailVerificationCode;
use App\Mail\EmailVerificationCodeMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\pL\pM]+(?:[ \'\-][\pL\pM]+)*$/u',
                new ValidGuildName,
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'string', 'min:10', 'regex:/[A-Z]/', 'regex:/[^A-Za-z0-9]/'],
        ]);

        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ]);

        $code = (string) random_int(100000, 999999);
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $code));
        event(new Registered($user));
        $request->session()->put('verification_user_id', $user->id);

        return redirect()->route('verification.notice');
    }
}
