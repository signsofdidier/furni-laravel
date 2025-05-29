<?php

namespace App\Helpers;

use App\Models\Color;
use App\Models\Product;

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
            $product = Product::find($product_id, ['id', 'name', 'price', 'images', 'slug']);
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
            // Update hoeveelheid
            $cart_items[$existing_item]['quantity'] = $quantity;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            $product = Product::find($product_id, ['id', 'name', 'price', 'images', 'slug']);
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
        return array_sum(array_column($items, 'total_amount'));
    }
}
