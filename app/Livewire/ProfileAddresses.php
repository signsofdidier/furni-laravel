<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class ProfileAddresses extends Component
{
    // Hierin komen alle adressen van de ingelogde gebruiker
    public $addresses;
    // Bepaalt of het adres-formulier zichtbaar is
    public $showForm = false;
    // Id van het adres dat aan het editen is (null als nieuw)
    public $edit_id = null;

    // Adresvelden (voor het formulier)
    public $first_name, $last_name, $phone, $street_address, $city, $state, $zip_code;

    public function mount() {
        // Bij laden van het component: direct adressen ophalen
        $this->refreshAddresses();
    }

    // HAALT ADDRESSEN OP VAN DE INGELOGDE GEBRUIKER, meest recent eerst
    public function refreshAddresses() {
        $this->addresses = Auth::user()->addresses()->latest()->get();
    }

    // Toon formulier voor nieuw adres, maak alles leeg
    public function showCreateForm() {
        // Reset edit_id en alle adresvelden
        $this->reset(['edit_id', 'first_name', 'last_name', 'phone', 'street_address', 'city', 'state', 'zip_code']);
        $this->showForm = true;
    }

    // Formulier tonen om een bestaand adres te bewerken
    public function edit($id) {
        // Zoek adres van de huidige user, faalt als het niet van jou is
        $adr = Address::where('user_id', Auth::id())->findOrFail($id);
        // Zet alle velden op wat er in de database staat, zodat je kan editen
        $this->edit_id = $adr->id;
        $this->first_name = $adr->first_name;
        $this->last_name = $adr->last_name;
        $this->phone = $adr->phone;
        $this->street_address = $adr->street_address;
        $this->city = $adr->city;
        $this->state = $adr->state;
        $this->zip_code = $adr->zip_code;
        $this->showForm = true; // Toon het formulier
    }

    // Adres opslaan (nieuw of update)
    public function save() {
        $this->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
        ]);

        // Alle data die we willen opslaan in 1 array
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
            // Als edit_id gevuld is: update bestaand adres
            Address::where('user_id', Auth::id())->findOrFail($this->edit_id)->update($data);
        } else {
            // Nieuw adres aanmaken
            Address::create($data);
        }
        $this->showForm = false; // Form wegdoen
        $this->refreshAddresses(); // Lijstje opnieuw ophalen
    }

    // Verwijder een adres van de huidige gebruiker
    public function delete($id) {
        Address::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->refreshAddresses();
    }

    // Annuleer het formulier, toon gewoon lijst van adressen terug
    public function cancel() {
        $this->showForm = false;
    }

    public function render() {
        return view('livewire.profile-addresses');
    }
}
