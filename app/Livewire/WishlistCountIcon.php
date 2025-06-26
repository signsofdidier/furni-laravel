<?php

namespace App\Livewire;

use App\Models\Wishlist;
use Livewire\Component;

class WishlistCountIcon extends Component
{
    // Houdt het aantal wishlist-items bij voor de ingelogde gebruiker
    public $count = 0;

    public function mount(){
        // Tel wishlist-items voor deze gebruiker
        // Neem id van ingelogde user en zoek in de wishlist-tabel waar de kolom user_id gelijk is aan de ingelogde gebruiker.
        if(auth()->check()){
            $this->count = Wishlist::where('user_id', auth()->user()->id)->count();
        }
    }

    // Luistert naar events met de naam "wishlistUpdated" vanuit andere Livewire-componenten
    protected $listeners = [
        'wishlistUpdated' => 'updateCount'
    ];

    // Wordt uitgevoerd als het wishlistUpdated-event wordt verzonden
    public function updateCount(){
        // Herbereken de wishlist-count (bv na toevoegen/verwijderen)
        $this->count = Wishlist::where('user_id', auth()->user()->id)->count();
    }

    // Rendert het bijbehorende Blade-component (bijv. icoon + teller)
    public function render()
    {
        return view('livewire.wishlist-count-icon');
    }
}
