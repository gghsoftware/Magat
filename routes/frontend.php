<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CustomerController;
use App\Http\Controllers\Frontend\Auth\UserLoginController;
use App\Http\Controllers\Frontend\PackageController;

/**
 * Guest-only auth
 */
Route::middleware('guest')->name('frontend.')->group(function () {
    // Login
    Route::get('user/login',  [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('user/login', [UserLoginController::class, 'login'])->name('login.post');

    // Register
    Route::get('user/register',  [UserLoginController::class, 'showRegisterForm'])->name('register');
    Route::post('user/register', [UserLoginController::class, 'register'])->name('register.post'); // ⬅️ add this

    // Forgot password
    Route::get('user/password/reset', [UserLoginController::class, 'showForgotForm'])->name('password.request');
});


/**
 * Logout
 */
Route::post('user/logout', [UserLoginController::class, 'logout'])
    ->name('frontend.logout')
    ->middleware('auth');

/**
 * Main site (prefix: frontend.)
 */
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact.index');

    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::middleware('auth')->group(function () {
        Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');
        Route::post('/packages/{package:slug}/quote', [PackageController::class, 'quote'])->name('packages.quote');
        Route::get('/packages/{package:slug}/payment', [PackageController::class, 'payment'])->name('packages.payment'); // if you have it
        Route::post('/packages/{package:slug}/submit', [PackageController::class, 'submit'])->name('packages.submit');
    });

    // Cart (✅ no leading dots)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout (✅ single GET)
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Account (protected)
    Route::middleware('auth')->group(function () {
        Route::get('/account', [CustomerController::class, 'dashboard'])->name('account.dashboard');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('account.orders');
    });
});
