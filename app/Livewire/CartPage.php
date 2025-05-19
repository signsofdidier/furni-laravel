<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - E-Commerce')]
class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;

    public function mount(){
        $this->cart_items = CartManagement::getCartItemsFromSession(); // ⬅️ aangepast
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    public function removeItem($product_id){
        $this->cart_items = CartManagement::removeCartItem($product_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items))->to(Navbar::class);
    }

    public function increaseQuantity($product_id){
        CartManagement::incrementQuantityToCartItem($product_id); // ⬅️ aangepast
        $this->cart_items = CartManagement::getCartItemsFromSession(); // ⬅️ aangepast
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // zorgt ervoor dat de navbar count zich ook aanpast bij het increasen
        $this->dispatch('update-cart-count', total_count: array_sum(array_column($this->cart_items, 'quantity')))->to(Navbar::class);

    }

    public function decreaseQuantity($product_id){
        CartManagement::decrementQuantityToCartItem($product_id); // ⬅️ aangepast
        $this->cart_items = CartManagement::getCartItemsFromSession(); // ⬅️ aangepast
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // zorgt ervoor dat de navbar count zich ook aanpast bij het decreasen
        $this->dispatch('update-cart-count', total_count: array_sum(array_column($this->cart_items, 'quantity')))->to(Navbar::class);

    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
