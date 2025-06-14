<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Wishlist;
use Livewire\Component;

class WishlistButton extends Component
{
    // De product waar deze wishlist-knop betrekking op heeft
    public Product $product;

    // Boolean die bijhoudt of het product al in de wishlist zit
    public bool $isInWishlist = false;

    /**
     * Wordt automatisch aangeroepen bij initialisatie van het component.
     * Bepaalt of het huidige product al in de wishlist staat van de ingelogde gebruiker.
     */
    public function mount(Product $product)
    {
        $this->isInWishlist = auth()->check()
            && auth()->user()
                ->wishlist()
                ->where('product_id', $product->id)
                ->exists();
    }

    /**
     * Deze functie wordt aangeroepen wanneer de gebruiker op het wishlist-hartje klikt.
     * Het voegt het product toe aan of verwijdert het uit de wishlist.
     */
    public function toggleWishlist()
    {
        // Als de gebruiker niet is ingelogd, stuur hem dan naar de loginpagina
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Controleer of het product al in de wishlist staat
        $existing = $user->wishlist()->where('product_id', $this->product->id)->first();

        if ($existing) {
            // Verwijder uit wishlist als het er al in staat
            $existing->delete();
            $this->isInWishlist = false;
        } else {
            // Voeg toe aan wishlist als het er nog niet in staat
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $this->product->id,
            ]);
            $this->isInWishlist = true;
        }

        // dispatched een Livewire event zodat andere componenten zoals WishlistPage kunnen updaten
        $this->dispatch('wishlistUpdated');
    }

    /**
     * Rendert het Livewire-component en geeft de bijhorende Blade view terug.
     */
    public function render()
    {
        return view('livewire.wishlist-button');
    }
}
