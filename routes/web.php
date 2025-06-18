<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MfaController; // ✅ Add this for MFA
use Laravel\Fortify\Fortify;


Route::get('/', function () {
    return view('welcome');
});

// Authentication routes


Route::get('/mfa/verify', [MfaController::class, 'showVerifyForm'])->name('mfa.verify.form');
Route::post('/mfa/verify', [MfaController::class, 'verifyCode'])->name('mfa.verify');
Route::post('/mfa/resend', [MfaController::class, 'resendCode'])->name('mfa.resend');

// Redirect to the todo list page after login, instead of /home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Todo routes
Route::resource('todo', TodoController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');


});






