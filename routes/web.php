<?php

use App\Livewire\About\About;
use App\Livewire\Cart\Cart;
use App\Livewire\Collections\Collections;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\TransactionHistory;
use App\Livewire\DetailProduct\DetailProduct;
use App\Livewire\Home\Home;
use App\Livewire\Login\Login;
use App\Livewire\Profile\Profile;
use App\Livewire\Register\Register;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::livewire('/', Home::class)->name('home');
Route::livewire('/about', About::class)->name('about');
Route::livewire('/collections/{kategori?}', Collections::class)->name('collections');
Route::livewire('/produk/{id}', DetailProduct::class)->name('product.show');
Route::livewire('/keranjang', Cart::class)->name('cart');
Route::livewire('/login', Login::class)->name('login');
Route::livewire('/register', Register::class)->name('register');

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', Dashboard::class)->name('dashboard');
    Route::livewire('/profile', Profile::class)->name('profile');
});

Route::livewire('/transaction-history', TransactionHistory::class)->name('transaction.history');

// Route::get('/', function () {
//     return view('welcome');
// });
