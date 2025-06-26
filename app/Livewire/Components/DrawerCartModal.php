<?php

namespace App\Livewire\Components;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class DrawerCartModal extends Component
{
    public $total_count = 0; // totaal aantal producten in de winkelmand
    public $cart_items = []; // lijst met alles wat in de cart zit
    public $grand_total; // totaalbedrag van alles samen

    // TELT ALLE ITEMS IN DE CART + QUANTITY VAN EEN ITEM
    public function mount(){
        // haal items op uit de sessie
        $this->cart_items = CartManagement::getCartItemsFromSession();

        // MAX VOORAAD VAN DEZE KLEUR OPSLAAN
        // &$item = zodat we rechtstreeks in de array schrijven
        foreach ($this->cart_items as &$item) {
            // PRODUCT OPHALEN VIA PRODUCT_ID
            $product = Product::find($item['product_id']);
            // MAX VOORAAD VAN DEZE KLEUR OPSLAAN, anders 0 als product niet gevonden is
            $item['max_stock'] = $product?->stockForColorId($item['color_id']) ?? 0;
        }

        // BEREKEN TOTAALBEDRAG
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // HOEVEELHEID ITEMS IN CART
        $this->total_count = array_sum(array_column($this->cart_items, 'quantity'));
    }

    // PAST COUNT AAN IN DE NAVBAR
    #[On('update-cart-count')]
    public function updateCartCount($total_count){
        $this->total_count = $total_count;
    }

    // REFRESH CART na toevoegen, verwijderen, quantity wijzigen enz.
    #[On('cart-updated')]
    public function refreshCart()
    {
        $this->cart_items = CartManagement::getCartItemsFromSession(); // haal items op uit de sessie

        // MAX VOORAAD VAN DEZE KLEUR OPSLAAN
        // &$item = zodat we rechtstreeks in de array schrijven
        foreach ($this->cart_items as &$item) {
            $product = Product::find($item['product_id']);
            $item['max_stock'] = $product?->stockForColorId($item['color_id']) ?? 0;
        }

        // BEREKEN TOTAALBEDRAG
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        // HOEVEELHEID ITEMS IN CART
        $this->total_count = array_sum(array_column($this->cart_items, 'quantity'));
    }

    // VERWIJDER EEN ITEM VAN DE WINKELMAND
    public function removeItem($product_id, $color_id){
        $this->cart_items = CartManagement::removeCartItem($product_id, $color_id); // haal items op uit de sessie
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items); // bereken totaalbedrag
        // PAST COUNT AAN IN DE NAVBAR
        $this->dispatch('update-cart-count', total_count: count($this->cart_items))->to(Navbar::class);
        // refresh cart
        $this->dispatch('cart-updated');
    }

    // VERHOOG EEN ITEM VAN DE WINKELMAND
    public function increaseQuantity($product_id, $color_id)
    {
        // HUIDIGE QUANTITY in cart voor dit item
        $currentQuantity = CartManagement::getQuantityInCart($product_id, $color_id);

        // CHECK VOORAAD IN DATABASE
        $product = Product::find($product_id);
        // Als het product niet meer bestaat (bv. verwijderd), stop dan de functie
        if (!$product) return;

        // Haal de beschikbare voorraad op voor deze specifieke kleurvariant
        $availableStock = $product->stockForColorId($color_id);

        // Controleer of de gevraagde hoeveelheid de beschikbare voorraad overschrijdt
        if ($currentQuantity >= $availableStock) {
            // Toon een foutmelding als er niet genoeg voorraad is
            session()->flash('error', 'Not enough stock for this product.');
            return;
        }

        // Verhoog de hoeveelheid van het item in de sessie-winkelmand
        CartManagement::incrementQuantityToCartItem($product_id, $color_id);

        // REFRESH DE CART (items, totalen, stock) zodat de UI up-to-date blijft
        $this->refreshCart();
    }

    // VERLAAG EEN ITEM VAN DE WINKELMAND
    public function decreaseQuantity($product_id, $color_id){
        // Verlaag de hoeveelheid van dit product (en kleur) in de winkelmand met 1
        CartManagement::decrementQuantityToCartItem($product_id, $color_id);

        // Haal de bijgewerkte cart-items opnieuw op uit de sessie
        $this->cart_items = CartManagement::getCartItemsFromSession();

        // Bereken opnieuw het totaalbedrag van de winkelmand
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);


        // Verstuur een event naar de Navbar component om de cart-count (hoeveelheid) bij te werken
        $this->dispatch('update-cart-count', total_count: array_sum(array_column($this->cart_items,
            'quantity')))->to(Navbar::class);

        // Laat andere componenten weten dat de cart is aangepast
        $this->dispatch('cart-updated');

    }

    // CART RESET KNOP
    public function clearCart()
    {
        CartManagement::clearCartItems();
        $this->cart_items = [];
        $this->grand_total = 0;
        $this->total_count = 0;

        // Update de navbar count
        $this->dispatch('update-cart-count', total_count: 0)->to(Navbar::class);
        $this->dispatch('cart-updated');
    }



    public function render()
    {
        return view('livewire.components.drawer-cart-modal');
    }
}
