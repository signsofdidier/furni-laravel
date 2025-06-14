<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileForm extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $profile_photo;
    public $new_profile_photo;

    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;

    public function mount(){
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->profile_photo = $user->profile_photo_path;

        $address = $user->address;

        if($address){
            $this->first_name = $address->first_name;
            $this->last_name = $address->last_name;
            $this->phone = $address->phone;
            $this->street_address = $address->street_address;
            $this->city = $address->city;
            $this->state = $address->state;
            $this->zip_code = $address->zip_code;
        }
    }

    public function save(){
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'new_profile_photo' => 'nullable|image|max:2048',

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
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

            // Upload nieuwe foto
            $path = $this->new_profile_photo->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }


        $user->save();

        $user->address()->updateOrCreate([],[
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code
        ]);

        // LIVEWIRE SWEETALERT
        $this->dispatch('profile-alert');

    }

    public function render()
    {
        return view('livewire.profile-form');
    }
}
