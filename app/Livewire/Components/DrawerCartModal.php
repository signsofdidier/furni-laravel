<?php

namespace App\Livewire\Components;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\On;
use Livewire\Component;

class DrawerCartModal extends Component
{
    public $total_count = 0;

    public $cart_items = [];
    public $grand_total;

    // telt alle items in de cart op maar ook de quantity van een item
    public function mount(){
        $this->total_count = array_sum(array_column(CartManagement::getCartItemsFromSession(), 'quantity'));

        $this->cart_items = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    // zorgt ervoor dat de navbar count zich ook aanpast
    #[On('update-cart-count')]
    public function updateCartCount($total_count){
        $this->total_count = $total_count;
    }

    // refresh cart na toevoegen van product of aanpassing in de cart
    #[On('cart-updated')]
    public function refreshCart()
    {
        $this->cart_items = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->total_count = array_sum(array_column($this->cart_items, 'quantity'));
    }


    public function removeItem($product_id){
        $this->cart_items = CartManagement::removeCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items))->to(Navbar::class);
        $this->dispatch('cart-updated');
    }

    public function increaseQuantity($product_id){
        CartManagement::incrementQuantityToCartItem($product_id);
        $this->cart_items = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // zorgt ervoor dat de navbar count zich ook aanpast bij het increasen
        $this->dispatch('update-cart-count', total_count: array_sum(array_column($this->cart_items, 'quantity')))->to(Navbar::class);
        $this->dispatch('cart-updated');

    }

    public function decreaseQuantity($product_id){
        CartManagement::decrementQuantityToCartItem($product_id);
        $this->cart_items = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // zorgt ervoor dat de navbar count zich ook aanpast bij het decreasen
        $this->dispatch('update-cart-count', total_count: array_sum(array_column($this->cart_items, 'quantity')))->to(Navbar::class);
        $this->dispatch('cart-updated');

    }



    public function render()
    {
        return view('livewire.components.drawer-cart-modal');
    }
}
