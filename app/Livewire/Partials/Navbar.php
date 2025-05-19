<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{

    public $total_count = 0;

    public function mount(){
        // telt alle items in de cart op maar ook de quantity van een item
        $this->total_count = array_sum(array_column(CartManagement::getCartItemsFromSession(), 'quantity'));

    }

    #[On('update-cart-count')]
    public function updateCartCount($total_count){
        $this->total_count = $total_count;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
