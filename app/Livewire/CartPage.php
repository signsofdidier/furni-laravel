<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Setting;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - E-Commerce')]
class CartPage extends Component
{
    public array $cart_items = [];
    public float $sub_total = 0.0;

    // property om verzendkosten op te slaan
    public float $shipping_amount       = 0.0;
    // property om threshold (gratis verzending) op te slaan
    public float $free_shipping_threshold = 0.0;

    // property voor het eindbedrag (items + shipping – discount)
    public float $grand_total = 0.0;

    public function mount(): void
    {
        // deze helper-methode haalt de sessie opnieuw op en berekent de totalen, zodat je dat niet in elke functie hoeft te herhalen.
        $this->loadCart();
    }


    protected function loadCart(): void
    {
        // 1) Haal items op
        $this->cart_items  = CartManagement::getCartItemsFromSession();

        // 2) Bereken “sub_total” = sum(total_amount) van alle items
        $this->sub_total = CartManagement::calculateGrandTotal($this->cart_items);

        // 3) Haal de free_shipping_threshold uit de settings (één record)
        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0.0;

        // 4) Bereken de verzendkosten via de helper
        $this->shipping_amount = CartManagement::calculateShippingAmount($this->cart_items);

        // 5) Bereken het grand totaal (“grand_total”) = sub_total + shipping_amount
        $this->grand_total = $this->sub_total + $this->shipping_amount;
    }

    /**
     * Verwijdert één cart-item (product + kleur) uit de sessie,
     * herlaadt de cart en stuurt de nieuwe count naar de navbar.
     */
    public function removeItem(int $product_id, ?int $color_id = null): void
    {
        // 1) Verwijder het item in de sessie
        $this->cart_items = CartManagement::removeCartItem($product_id, $color_id);

        // 2) Herlaad ALLES (sub_total, shipping_amount, grand_total, enz.)
        $this->loadCart();

        // 3) Update de cart-count in de Navbar
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

        // Herlaad ALLES
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

        // Herlaad ALLES
        $this->loadCart();

        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    public function render()
    {
        return view('livewire.cart-page', [
            'cart_items' => $this->cart_items,
            'sub_total' => $this->sub_total,
            'shipping_amount'=> $this->shipping_amount,
            'free_shipping_threshold' => $this->free_shipping_threshold,
            'grand_total' => $this->grand_total,
        ]);
    }
}
