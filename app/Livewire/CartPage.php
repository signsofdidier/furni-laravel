<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - E-Commerce')]
class CartPage extends Component
{
    // Alle producten in de winkelmand (wordt telkens opnieuw geladen)
    public array $cart_items = [];
    // Subtotaal van alles samen in de cart (zonder verzendkosten)
    public float $sub_total = 0.0;

    public function mount(): void
    {
        // deze helper-methode haalt de sessie opnieuw op en berekent de totalen,
        // zodat je dat niet in elke functie hoeft te herhalen.
        $this->loadCart();
    }

    /* HAALT DE LATESTE STAND VAN DE CART OP */
    // en vult voor elk item de max_stock (voorraad) aan, plus berekent de sub_total
    protected function loadCart(): void
    {
        // 1) Haal alle items uit de cart uit de sessie
        $this->cart_items  = CartManagement::getCartItemsFromSession();

        // 2) Voeg bij elk item het maximum voorraad toe, afhankelijk van gekozen kleur
        foreach ($this->cart_items as &$item) {
            $product = Product::find($item['product_id']); // zoek product op via id
            // max_stock is voorraad voor deze kleur, of 0 als product niet meer bestaat
            $item['max_stock'] = $product?->stockForColorId($item['color_id']) ?? 0;
        }

        // 3) Bereken het sub-totaal van de cart (zonder shipping)
        $this->sub_total = CartManagement::calculateGrandTotal($this->cart_items);
    }

    /**
     * Verwijdert één cart-item (product + kleur) uit de sessie,
     * herlaadt de cart en stuurt de nieuwe count naar de navbar.
     */
    public function removeItem(int $product_id, ?int $color_id = null): void
    {
        // 1) Verwijder het item in de cart sessie
        $this->cart_items = CartManagement::removeCartItem($product_id, $color_id);

        // 2) Herlaad ALLES (sub_total, shipping_amount, grand_total, enz.)
        $this->loadCart();

        // 3) Update de cart-count in de Navbar
        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);
    }

    /**
     * INCREASE QUANTITY van een cart-item (product + kleur),
     * herlaadt de cart en update de navbar-count.
     */
    public function increaseQuantity(int $product_id, ?int $color_id = null): void
    {
        // 1. Check huidige hoeveelheid in cart
        $currentQuantity = CartManagement::getQuantityInCart($product_id, $color_id);

        // 2. Haal stock op voor dit product en deze kleur
        $product = Product::find($product_id);
        if (!$product) return;

        // 3. Haal stock voorraad op voor deze kleur
        $availableStock = $product->stockForColorId($color_id);

        // 4. QUANTITY MAG NIET BOVEN DE STOCK GAAN
        if ($currentQuantity >= $availableStock) {
            // Foutmelding tonen
            session()->flash('error', 'Not enough stock for this product.');
            return;
        }

        // 5. Verhoog quantity
        CartManagement::incrementQuantityToCartItem($product_id, $color_id);

        // 6. Refresh cart + update navbar
        $this->loadCart();
        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);

        // 7. Update cart in de drawer modal
        $this->dispatch('cart-updated');

    }

    /**
     * DECREASE QUANTITY van een cart-item (mits > 1),
     * herlaadt de cart en update de navbar-count.
     */
    public function decreaseQuantity(int $product_id, ?int $color_id = null): void
    {
        // 1. Verlaag quantity via helper
        CartManagement::decrementQuantityToCartItem($product_id, $color_id);

        // 2. Refresh cart + update navbar
        $this->loadCart();
        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);


        // Update cart in de drawer modal
        $this->dispatch('cart-updated');
    }

    // CART RESET KNOP
    public function clearCart()
    {
        CartManagement::clearCartItems();
        $this->cart_items = [];
        $this->sub_total = 0;

        // Update de navbar count
        $this->dispatch('update-cart-count',
            array_sum(array_column($this->cart_items, 'quantity'))
        )->to(Navbar::class);

        // Update cart in de drawer modal
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.cart-page', [
            'cart_items' => $this->cart_items,
            'sub_total' => $this->sub_total,
        ]);
    }
}
