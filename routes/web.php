<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
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

Route::get('/createListing', [MainController::class, 'create'])->name('listing.create')->middleware('auth');;
Route::post('/listings/search', [MainController::class, 'search'])->name('listing.search')->middleware('auth');;
Route::post('/listings', [MainController::class, 'store'])->name('listing.store')->middleware('auth');;

Route::post('/addToCart', [MainController::class, 'addToCart'])->name('cart.add')->middleware('auth');
Route::get('/cart', [MainController::class, 'cart'])->name('cart')->middleware('auth');
Route::delete('/cart/{cartItemId}', [MainController::class, 'removeFromCart'])->name('cart.remove')->middleware('auth');

Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::get('/orders', [OrderController::class, 'orders'])->name('orders')->middleware('auth');
Route::get('/order/{order_id}', [OrderController::class, 'show'])->name('order.show')->middleware('auth');
Route::post('/order/{order_id}/message', [OrderController::class, 'sendMessage'])->name('order.send-message')->middleware('auth');
Route::post('/order/{order_id}/update-status', [OrderController::class, 'updateStatus'])->name('order.update-status')->middleware('auth');
Route::get('/order/{order_id}/pay', [OrderController::class, 'payNow'])->name('order.pay')->middleware('auth');
Route::post('/order/{order_id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel')->middleware('auth');