<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Users\Models\EmailVerificationCode;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCodeMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('verification_user_id')) {
            return redirect()->route('register');
        }

        return view('auth.verify-email');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = User::findOrFail($request->session()->get('verification_user_id'));
        $verification = EmailVerificationCode::query()
            ->where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $verification || ! Hash::check($validated['code'], $verification->code)) {
            return back()->withErrors(['code' => 'That verification code is invalid or expired.']);
        }

        $user->forceFill(['email_verified_at' => now()])->save();
        EmailVerificationCode::query()->where('user_id', $user->id)->delete();
        Auth::login($user);
        $request->session()->forget('verification_user_id');
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('verification_user_id');

        if (! $userId) {
            return redirect()->route('register');
        }

        $user = User::findOrFail($userId);
        $code = (string) random_int(100000, 999999);

        EmailVerificationCode::query()->where('user_id', $user->id)->delete();
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($user->email)->send(new EmailVerificationCodeMail($user, $code));

        return back()->with('status', 'A new verification code has been sent.');
    }
}
