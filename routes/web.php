<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [MainController::class, 'home'])->name('home');

Route::get('/signup', [UserController::class, 'signupForm'])->name('signup');
Route::post('/signup', [UserController::class, 'signup'])->name('signup.post');

Route::get('/login', [UserController::class, 'loginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');

Route::get('/profile', [UserController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::post('/profile/avatar', [UserController::class, 'uploadAvatar'])->name('avatar.upload');
Route::post('/profile/avatar/delete', [UserController::class, 'deleteAvatar'])->name('avatar.delete');

Route::get('/marketplace', [MainController::class, 'marketplace'])->name('marketplace');
Route::get('/release/{vinyl_id}', [MainController::class, 'show'])->name('vinyl.details');

