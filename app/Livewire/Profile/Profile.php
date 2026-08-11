<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $photo;

    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
        ]);

        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ])->save();

        session()->flash('profile_updated', 'Profile information updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('password_updated', 'Password updated successfully.');
    }

    public function updatePhoto()
    {
        $this->validate([
            'photo' => 'required|image|max:2048', // 2MB max
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $this->photo->store('profile-photos', 'public');

        $user->update([
            'profile_photo_path' => $path,
        ]);

        $this->reset('photo');
        session()->flash('photo_updated', 'Profile photo updated successfully.');
    }

    public function render()
    {
        return view('livewire.profile.profile');
    }
}
