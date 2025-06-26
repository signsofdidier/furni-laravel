<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

// ProfileForm.php
class ProfileForm extends Component
{
    use WithFileUploads; // Livewire trait om file uploads te laten werken

    public $name;
    public $email;
    public $profile_photo;
    public $new_profile_photo;

    public function mount() {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_photo = $user->profile_photo_path;
    }

    public function save() {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'new_profile_photo' => 'nullable|image|max:2048',
        ]);
        $user = Auth::user();
        $user->name = $this->name;
        $user->email = $this->email;

        // PROFIEL FOTO UPLOAD
        if ($this->new_profile_photo) {
            // Verwijder oude foto als die bestaat
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Upload de nieuwe foto naar 'profile-photos/' op de public disk
            $path = $this->new_profile_photo->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        // SWEETALERT voor profile alerts
        $this->dispatch('profile-alert');
    }

    public function render() {
        return view('livewire.profile-form');
    }
}

