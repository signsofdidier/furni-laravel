<?php

namespace App\Livewire;

use Livewire\Component;

class WishlistPage extends Component
{
    public $products;

    // Luistert naar events van andere componenten
    protected $listeners = ['wishlistUpdated' => 'refreshProducts'];

    public function mount()
    {
        // Bij het laden van de pagina (component): haal direct alle wishlist producten op
        $this->loadWishlistProducts();
    }

    // Deze functie wordt opgeroepen als het event 'wishlistUpdated' afgaat
    public function refreshProducts()
    {
        $this->loadWishlistProducts();
    }

    //  functie die alle producten uit de wishlist ophaalt
    private function loadWishlistProducts()
    {
        // Haal alle wishlist items van de ingelogde user op,
        // laad meteen de bijhorende product-relatie (anders zijn het enkel id's),
        // en plak enkel de product modellen eruit.
        $this->products = auth()
            ->user()
            ->wishlist()
            ->with('product')
            ->get()
            ->pluck('product');
    }

    public function render()
    {
        return view('livewire.wishlist-page', [
            'products' => $this->products
        ]);
    }
}
