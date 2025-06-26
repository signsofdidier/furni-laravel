<?php

namespace App\Helpers;

use App\Models\Color;
use App\Models\Product;
use App\Models\Setting;

class CartManagement {

    // ========================
    // ITEM TOEVOEGEN AAN CART (1 STUK)
    // ========================

    // 1 stuk van een bepaald product en kleur toevoegen aan de cart.
    // Zoekt eerst of het al in de cart zit: ja → quantity +1, nee → nieuw item toevoegen.
    // Na afloop: cart wordt opgeslaan in de sessie.
    static public function addItemToCart(int $product_id, ?int $color_id = null): int{

        // Haal alle huidige cart items op uit de sessie
        $cart_items = self::getCartItemsFromSession();

        $existing_item = null; // ITEM DIE AL BESTAAT

        // ZOEK OF DIT PRODUCT EN KLEUR AL IN DE CART ZIT
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id && ($item['color_id'] ?? null) == $color_id)
            {
                $existing_item = $key; // sla de index op
                break; // stop zoeken
            }
        }

        if ($existing_item !== null) {
            // Item bestaat al?: verhoog aantal met 1
            $cart_items[$existing_item]['quantity']++;
            // Verhoog totale prijs (quantity * unit_amount)
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        } else {
            // NIEUW PRODUCT: haal gegevens op uit de database
            $product = Product::find($product_id, ['id', 'name', 'price', 'images', 'slug', 'shipping_cost']);
            $color = $color_id ? Color::find($color_id) : null; // haal kleur op als er ene is

            if($product){
                // Voeg product toe aan de cart array
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

        // OPSLAAN IN DE SESSIE (cart onthouden)
        self::saveCartItemsToSession($cart_items);

        // GEEF AANTAL PRODUCTEN IN DE CART
        return count($cart_items);
    }

    // ========================
    // ADD ITEM TO CART MET QUANTITY
    // ========================
    // Voeg meerdere aantal stuks van een product en kleur toe aan de cart
    // Werkt hetzelfde als addItemToCart, maar je kan meer dan 1 tegelijk toevoegen.
    static public function addItemToCartWithQuantity(int $product_id, int $quantity, ?int $color_id = null): int
    {
        // QUANTITY MAG NOOIT ONDER 1
        if ($quantity < 1) {
            $quantity = 1;
        }

        $cart_items = self::getCartItemsFromSession(); // Haal alle huidige cart items op
        $existing_item = null; // ITEM DIE AL BESTAAT

        // Zoek of item met die kleur en id al in de cart zit
        foreach($cart_items as $key => $item){
            if(
                $item['product_id'] == $product_id && ($item['color_id'] ?? null) === $color_id
            ){
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            // Item bestaat: tel aantal op bij het bestaande aantal
            $cart_items[$existing_item]['quantity'] += $quantity;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        } else {
            // Item bestaat niet: nieuw item toevoegen
            $product = Product::find($product_id, ['id', 'name', 'price', 'images', 'slug', 'shipping_cost']);
            $color = $color_id ? Color::find($color_id) : null; // haal kleur op

            if($product){
                // Voeg product toe aan de cart array
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

        // OPSLAAN IN DE SESSIE (cart onthouden)
        self::saveCartItemsToSession($cart_items);

        // GEEF AANTAL PRODUCTEN IN DE CART
        return count($cart_items);
    }

    // ========================
    // VERWIJDER ITEM OP BASIS VAN PRODUCT + KLEUR
    // ========================
    static public function removeCartItem(int $product_id, ?int $color_id = null): array
    {
        // Haal de huidige cart items op uit de sessie
        $cart_items = self::getCartItemsFromSession();

        // Loop over elk item in de cart
        foreach($cart_items as $key => $item){
            // Is dit het product en de juiste kleur?
            if($item['product_id'] == $product_id && ($item['color_id'] ?? null) === $color_id)
            {
                // Ja? gooi dit item eruit
                unset($cart_items[$key]);
            }
        }

        // Reset de indexen, anders kan je rare gaten krijgen in de array
        $cart_items = array_values($cart_items);

        // Sla de aangepaste cart terug op in de sessie
        self::saveCartItemsToSession($cart_items);

        // Geef de nieuwe cart terug
        return $cart_items;
    }

    // SAVE CART ITEMS TO SESSION
    static public function saveCartItemsToSession($cart_items){
        // Zet alles wat in $cart_items zit (array met producten, kleuren, hoeveelheden, ...)
        // onder de key 'cart_items' in de sessie.
        // Zo blijft de winkelmand bewaard als je pagina's wisselt of herlaadt.
        session()->put('cart_items', $cart_items);
    }

    // CLEAR CART (wis alles uit de sessie)
    static public function clearCartItems(){
        session()->forget('cart_items');
    }

    /* GET CART ITEMS FROM SESSION  */
    // Haal de volledige cart array op uit de sessie
    static public function getCartItemsFromSession(){
        return session()->get('cart_items', []);
    }

    /* INCREMENT QUANTITY IN CART */
    // verhoog hoeveelheid voor specifiek item (product + kleur)
    static public function incrementQuantityToCartItem(int $product_id, ?int $color_id = null): void
    {
        // Haal de cart op uit de sessie
        $cart_items = self::getCartItemsFromSession();

        // Loop alles af, zoek het juiste item (zelfde product en kleur)
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id && ($item['color_id'] ?? null) === $color_id)
            {
                // Gevonden?: +1 bij quantity, en ook direct het totaalbedrag aanpassen
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                break; // Stop, je wil maar 1 item aanpassen
            }
        }

        // Nieuwe versie opslaan in de sessie
        self::saveCartItemsToSession($cart_items);
    }

    /* DECREMENT QUANTITY IN CART */
    // Verlaag de hoeveelheid van een item met 1 (minimaal 1, dus niet naar 0)
    static public function decrementQuantityToCartItem(int $product_id, ?int $color_id = null): void
    {
        // Haal de cart op uit de sessie
        $cart_items = self::getCartItemsFromSession();

        // Zoek item op, maar enkel verlagen als quantity > 1 (je mag geen negatieve hoeveelheden krijgen)
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id && ($item['color_id'] ?? null) === $color_id && $item['quantity'] > 1)
            {
                // Verlaag quantity met 1, pas totaalbedrag direct aan
                $cart_items[$key]['quantity']--;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                break;
            }
        }

        // Nieuwe versie opslaan in de sessie
        self::saveCartItemsToSession($cart_items);
    }

    /* CALCULATE GRAND TOTAL */
    /* NOOIT EEN NEGATIEF BEDRAG */
    // Tel gewoon alle totaalbedragen van elk item in de winkelmand op, excl shipping
    static public function calculateGrandTotal($items){
        // Haal alle 'total_amount' waarden uit elk item in de array en tel ze bij elkaar op
        // max( ... , 0) zorgt dat je NOOIT EEN NEGATIEF BEDRAG krijgt, zelfs als de array leeg is
        return max(array_sum(array_column($items, 'total_amount')), 0); // Minimaal 0
    }

    /**
     *  Bereken het totaal inclusief shipping:
     *  subtotal (sum total_amount) + shipping (of 0 bij gratis verzending).
     */
    /*public static function calculateTotalWithShipping(array $cart_items): float
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
    }*/

    /* SHIPPING COST*/
    // Bereken alleen de verzendkosten voor de cart, rekening houden met gratis verzending
    public static function calculateShippingAmount(array $cart_items): float
    {
        // 1. Eerst alles optellen, zodat je weet of je gratis verzending hebt
        $grand_total = self::calculateGrandTotal($cart_items);

        // 2. Haal gratis-verzending-threshold uit settings
        $setting   = Setting::first();
        $threshold = $setting->free_shipping_threshold ?? 0;

        // 3. Zoek de hoogste verzendkost uit alle items in de winkelwagen
        $maxShipping = 0;
        foreach ($cart_items as $item) {
            // Als er een verzendkost is, vergelijk deze met maxShipping
            if (isset($item['shipping_cost']) && $item['shipping_cost'] > $maxShipping) {
                $maxShipping = $item['shipping_cost'];
            }
        }

        // 4) Als er een positieve threshold is en sub_total >= threshold, is het gratis
        if ($threshold > 0 && $grand_total >= $threshold) {
            return 0;
        }

        // 5. Anders: betaal gewoon de duurste shipping uit je cart
        return $maxShipping;
    }

    /* GET QUANITY IN CART */
    // Telt hoeveel keer dit product en kleur nu in de cart zit
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
