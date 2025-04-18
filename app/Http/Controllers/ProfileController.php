<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function edit()
{
    return view('profile');
}

public function update(Request $request)
{
    $user = auth()->user();

    $data = $request->validate([
        'nickname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:8',
        'phone' => 'nullable|string',
        'city' => 'nullable|string',
        'avatar' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = $avatarPath;
    }

    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    $user->update($data);

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

public function delete()
{
    $user = auth()->user();
    $user->delete();

    return redirect('/')->with('message', 'Account deleted');
}
}
