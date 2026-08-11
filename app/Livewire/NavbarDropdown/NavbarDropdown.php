<?php

namespace App\Livewire\NavbarDropdown;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NavbarDropdown extends Component
{
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    public function render()
    {
        return view('livewire.navbar-dropdown.navbar-dropdown');
    }
}
