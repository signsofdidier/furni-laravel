<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class ProfileAddresses extends Component
{
    public $addresses;
    public $showForm = false;
    public $edit_id = null;

    // Adresvelden:
    public $first_name, $last_name, $phone, $street_address, $city, $state, $zip_code;

    public function mount() {
        $this->refreshAddresses();
    }

    public function refreshAddresses() {
        $this->addresses = Auth::user()->addresses()->latest()->get();
    }

    public function showCreateForm() {
        $this->reset(['edit_id', 'first_name', 'last_name', 'phone', 'street_address', 'city', 'state', 'zip_code']);
        $this->showForm = true;
    }

    public function edit($id) {
        $adr = Address::where('user_id', Auth::id())->findOrFail($id);
        $this->edit_id = $adr->id;
        $this->first_name = $adr->first_name;
        $this->last_name = $adr->last_name;
        $this->phone = $adr->phone;
        $this->street_address = $adr->street_address;
        $this->city = $adr->city;
        $this->state = $adr->state;
        $this->zip_code = $adr->zip_code;
        $this->showForm = true;
    }

    public function save() {
        $this->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'phone' => $this->phone,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
        ];

        if ($this->edit_id) {
            Address::where('user_id', Auth::id())->findOrFail($this->edit_id)->update($data);
        } else {
            Address::create($data);
        }
        $this->showForm = false;
        $this->refreshAddresses();
    }

    public function delete($id) {
        Address::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->refreshAddresses();
    }

    public function cancel() {
        $this->showForm = false;
    }

    public function render() {
        return view('livewire.profile-addresses');
    }
}
