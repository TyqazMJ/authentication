<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Auth::routes();

// Redirect to the todo list page after login, instead of /home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Todo routes
Route::resource('todo', TodoController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'delete'])->name('profile.delete');


});






