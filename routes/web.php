<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/about', 'pages::about')->name('about');
Route::livewire('/collections/{kategori?}', 'pages::collections')->name('collections');
Route::livewire('/produk/{id}', 'pages::detail-product')->name('product.show');
Route::livewire('/keranjang', 'pages::cart')->name('cart');
Route::livewire('/login', 'pages::login')->name('login');
Route::livewire('/register', 'pages::register')->name('register');

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

// Route::get('/', function () {
//     return view('welcome');
// });
