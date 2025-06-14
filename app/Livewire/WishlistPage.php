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
        $this->loadWishlistProducts();
    }

    public function refreshProducts()
    {
        $this->loadWishlistProducts();
    }

    private function loadWishlistProducts()
    {
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
