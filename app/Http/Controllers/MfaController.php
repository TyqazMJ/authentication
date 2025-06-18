<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\MfaCode;
use App\Models\User;

class MfaController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.mfa-verify'); // ✅ Make sure you create this blade file
    }

    public function verifyCode(Request $request)
{
    $request->validate([
        'code' => 'required|digits:6'
    ]);

    $userId = session('mfa:user:id');

    if (!$userId) {
        return redirect('/login')->withErrors(['email' => 'Session expired. Please log in again.']);
    }

    $mfa = MfaCode::where('user_id', $userId)
        ->where('code', $request->code)
        ->where('expires_at', '>=', now())
        ->first();

    if (!$mfa) {
        return back()->withErrors(['code' => 'Invalid or expired verification code.']);
    }

    $user = User::find($userId);

    if (!$user) {
        return redirect('/login')->withErrors(['email' => 'User not found. Please log in again.']);
    }

    $mfa->delete();

    Auth::login($user);
    $request->session()->regenerate();

    session()->forget('mfa:user:id');

    return redirect()->intended('/todo')->with('status', 'Login verified.');
}


    public static function sendMfaCode(User $user)
    {
        $code = random_int(100000, 999999);

        // ✅ Save in mfa_codes table
        MfaCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
            ]
        );

        // ✅ Send email to user
        Mail::raw("Your verification code is: {$code}", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Verification Code');
        });
    }
}
