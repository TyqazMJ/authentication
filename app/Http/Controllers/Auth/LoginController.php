<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $redirectTo = '/todo';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // ✅ Send MFA code to email
        \App\Http\Controllers\MfaController::sendMfaCode($user);

        // ✅ Log out temporarily to prevent access before verification
        Auth::logout();
        session(['mfa_user_id' => $user->id]);

        return redirect()->route('mfa.verify');
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
}

}
