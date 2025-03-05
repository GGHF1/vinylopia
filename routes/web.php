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

Route::get('/explore', [MainController::class, 'explore'])->name('explore');
Route::get('/explore/release/{vinyl_id}', [MainController::class, 'vinylrelease'])->name('vinyl.release');
Route::get('/explore/search', [MainController::class, 'exploreSearch'])->name('explore.search');

Route::get('/marketplace', [MainController::class, 'marketplace'])->name('marketplace');

Route::get('/artist', [MainController::class, 'artist'])->name('artist.show');
