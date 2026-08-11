<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

new class extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        // Menghitung total quantity dari session 'cart'
        $cart = session()->get('cart', []);
        $this->count = array_sum(array_column($cart, 'quantity'));
    }

    public function logout()
    {
        Auth::logout(); // Logout user
        Session::invalidate(); // Hapus sesi
        Session::regenerateToken(); // Buat token CSRF baru untuk keamanan

        return redirect('/'); // Redirect ke halaman utama
    }

    public function render()
    {
        return view('livewire.components.navbar.navbar');
    }
};
