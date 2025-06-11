<?php

namespace App\Helpers;

use App\Models\Color;
use App\Models\Product;
use App\Models\Setting;

class CartManagement {

    // voegt een product en kleur toe aan de cart
    static public function addItemToCart(int $product_id, ?int $color_id = null): int{
        $cart_items = self::getCartItemsFromSession();

        $existing_item = null;

        // Zoek bestaand item op basis van product + kleur
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id
                // controlleer kleur
                && ($item['color_id'] ?? null) == $color_id
            ){
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            // Verhoog hoeveelheid bij bestaand item
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] =
                $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        } else {
            // Haal product en kleur op
            $product = Product::find($product_id, ['id', 'name', 'price', 'images', 'slug', 'shipping_cost']);
            $color = $color_id ? Color::find($color_id) : null; // haal kleur op als er ene is

            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'color_id' => $color_id,
                    'color_name' => $color->name ?? null,
                    'color_hex' => $color->hex ?? null,
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                    'shipping_cost' => $product->shipping_cost,
                    'image' => $product->images[0] ?? null,
                ];
            }
        }

        self::saveCartItemsToSession($cart_items);
        return count($cart_items);
    }

    // voegt een product toe met specifieke hoeveelheid en kleur als die er is
    static public function addItemToCartWithQuantity(int $product_id, int $quantity, ?int $color_id = null): int
    {
        // QUANTITY NOOIT ONDER 1
        if ($quantity < 1) {
            $quantity = 1;
        }

        $cart_items = self::getCartItemsFromSession();
        $existing_item = null;

        foreach($cart_items as $key => $item){
            if(
                $item['product_id'] == $product_id
                && ($item['color_id'] ?? null) === $color_id
            ){
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            // Voeg toe aan de bestaande hoeveelheid
            $cart_items[$existing_item]['quantity'] += $quantity;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            $product = Product::find($product_id, ['id', 'name', 'price', 'images', 'slug', 'shipping_cost']);
            $color = $color_id ? Color::find($color_id) : null;

            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'color_id' => $color_id,
                    'color_name' => $color->name ?? null,
                    'color_hex' => $color->hex ?? null,
                    'quantity' => $quantity,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price * $quantity,
                    'shipping_cost' => $product->shipping_cost,
                    'image' => $product->images[0] ?? null,
                ];
            }
        }

        self::saveCartItemsToSession($cart_items);
        return count($cart_items);
    }

    // verwijder item op basis van product + kleur
    static public function removeCartItem(int $product_id, ?int $color_id = null): array
    {
        $cart_items = self::getCartItemsFromSession();

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id
            && ($item['color_id'] ?? null) === $color_id
            ){
                unset($cart_items[$key]);
            }
        }

        $cart_items = array_values($cart_items); // indexen resetten
        self::saveCartItemsToSession($cart_items);

        return $cart_items;
    }

    static public function saveCartItemsToSession($cart_items){
        session()->put('cart_items', $cart_items);
    }

    static public function clearCartItems(){
        session()->forget('cart_items');
    }

    static public function getCartItemsFromSession(){
        return session()->get('cart_items', []);
    }

    // verhoog hoeveelheid voor specifiek item (product + kleur)
    static public function incrementQuantityToCartItem(int $product_id, ?int $color_id = null): void
    {
        $cart_items = self::getCartItemsFromSession();
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id && ($item['color_id'] ?? null) === $color_id
            ){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::saveCartItemsToSession($cart_items);
    }

    // verlaag hoeveelheid voor specifiek item (product + kleur)
    static public function decrementQuantityToCartItem(int $product_id, ?int $color_id = null): void
    {
        $cart_items = self::getCartItemsFromSession();
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id &&
                ($item['color_id'] ?? null) === $color_id && $item['quantity'] > 1
            ){
                $cart_items[$key]['quantity']--;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::saveCartItemsToSession($cart_items);
    }

    static public function calculateGrandTotal($items){
        return max(array_sum(array_column($items, 'total_amount')), 0); // Minimaal 0
    }

    /**
     * Bereken het totaal inclusief shipping:
     *   subtotal (sum total_amount) + shipping (of 0 bij gratis verzending).
     */
    public static function calculateTotalWithShipping(array $cart_items): float
    {
        // 1) Bereken de subtotal (exclusief shipping)
        $subtotal = array_sum(array_column($cart_items, 'total_amount'));

        // 2) Bepaal de threshold (gratis verzending) uit settings
        $setting = Setting::first();
        $threshold = $setting->free_shipping_threshold ?? 0;

        // 3) Bereken de shipping
        $shipping = 0;
        foreach ($cart_items as $item) {
            $shipping += ($item['shipping_cost'] * $item['quantity']);
        }

        // 4) Als we boven de threshold zitten, is shipping gratis
        if ($subtotal >= $threshold) {
            $shipping = 0;
        }

        // 5) Return subtotal + shipping
        return $subtotal + $shipping;
    }

    /* SHIPPING COST*/
    public static function calculateShippingAmount(array $cart_items): float
    {
        // 1) Bereken sub_total zodat we kunnen checken op gratis verzending
        $grand_total = self::calculateGrandTotal($cart_items);

        // 2) Haal threshold op uit de instellingen
        $setting   = Setting::first();
        $threshold = $setting->free_shipping_threshold ?? 0;

        // 3) Zoek de hoogste verzendkost in de hele cart
        $maxShipping = 0;
        foreach ($cart_items as $item) {
            if (isset($item['shipping_cost']) && $item['shipping_cost'] > $maxShipping) {
                $maxShipping = $item['shipping_cost'];
            }
        }

        // 4) Als er een positieve threshold is én sub_total ≥ threshold, is het gratis
        if ($threshold > 0 && $grand_total >= $threshold) {
            return 0;
        }

        // 5) Anders is de verzendkost precies die maxShipping
        return $maxShipping;
    }

    // VOORKOM MEER VAN 1 VAN EEN PRODUCT IN DE CART DAN WAT IN STOCK IS
    // Geeft terug hoeveel stuks van een bepaald product + kleur al in de winkelwagen zitten
    public static function getQuantityInCart(int $product_id, ?int $color_id = null): int
    {
        // Haal huidige inhoud van de winkelwagen op uit de sessie
        $cart_items = self::getCartItemsFromSession();

        // Loop door elk item in de winkelwagen
        foreach ($cart_items as $item) {
            // Controleer of dit item hetzelfde product en dezelfde kleur heeft
            if (
                $item['product_id'] === $product_id &&
                ($item['color_id'] ?? null) === $color_id
            ) {
                // Als er een match is, geef de huidige hoeveelheid in de cart terug
                return $item['quantity'];
            }
        }

        // Als het product (met kleur) niet in de cart zit, geef 0 terug
        return 0;
    }

}
