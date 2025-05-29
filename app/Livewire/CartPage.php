<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - E-Commerce')]
class CartPage extends Component
{
    public array $cart_items = [];
    public float $grand_total = 0.0;

    public function mount(): void
    {
        // deze helper-methode haalt de sessie opnieuw op en berekent de totalen, zodat je dat niet in elke functie hoeft te herhalen.
        $this->loadCart();
    }


    protected function loadCart(): void
    {
        $this->cart_items  = CartManagement::getCartItemsFromSession();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    /**
     * Verwijdert één cart-item (product + kleur) uit de sessie,
     * herlaadt de cart en stuurt de nieuwe count naar de navbar.
     */
    public function removeItem(int $product_id, ?int $color_id = null): void
    {
        $this->cart_items = CartManagement::removeCartItem($product_id, $color_id);
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);

        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    /**
     * Verhoogt de hoeveelheid van een cart-item (product + kleur),
     * herlaadt de cart en update de navbar-count.
     */
    public function increaseQuantity(int $product_id, ?int $color_id = null): void
    {
        CartManagement::incrementQuantityToCartItem($product_id, $color_id);
        $this->loadCart();

        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    /**
     * Verlaagt de hoeveelheid van een cart-item (mits >1),
     * herlaadt de cart en update de navbar-count.
     */
    public function decreaseQuantity(int $product_id, ?int $color_id = null): void
    {
        CartManagement::decrementQuantityToCartItem($product_id, $color_id);
        $this->loadCart();

        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    public function render()
    {
        return view('livewire.cart-page', [
            'cart_items'  => $this->cart_items,
            'grand_total' => $this->grand_total,
        ]);
    }
}
