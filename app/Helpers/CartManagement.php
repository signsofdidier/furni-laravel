<?php

namespace App\Helpers;

use App\Models\Product;

class CartManagement {
    // add item to cart
    static public function addItemToCart($product_id){
        $cart_items = self::getCartItemsFromSession(); // ⬅️ aangepast

        $existing_item = null;

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            $product = Product::find($product_id, ['id', 'name', 'price', 'images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                    'image' => $product->images[0] ?? null,
                ];
            }
        }

        self::saveCartItemsToSession($cart_items); // ⬅️ aangepast
        return count($cart_items);
    }

    // add item with quantity
    static public function addItemToCartWithQuantity($product_id, $quantity){
        $cart_items = self::getCartItemsFromSession(); // ⬅️ aangepast

        $existing_item = null;

        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            $cart_items[$existing_item]['quantity'] = $quantity;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            $product = Product::find($product_id, ['id', 'name', 'price', 'images']);
            if($product){
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price * $quantity,
                    'image' => $product->images[0] ?? null,
                ];
            }
        }

        self::saveCartItemsToSession($cart_items); // ⬅️ aangepast
        return count($cart_items);
    }

    static public function removeCartItem($product_id){
        $cart_items = self::getCartItemsFromSession(); // ⬅️ aangepast
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                unset($cart_items[$key]);
            }
        }

        $cart_items = array_values($cart_items); // indexen resetten
        self::saveCartItemsToSession($cart_items); // ⬅️ aangepast

        return $cart_items;
    }

    static public function saveCartItemsToSession($cart_items){
        session()->put('cart_items', $cart_items); // ⬅️ aangepast
    }

    static public function clearCartItems(){
        session()->forget('cart_items'); // ⬅️ aangepast
    }

    static public function getCartItemsFromSession(){
        return session()->get('cart_items', []); // ⬅️ aangepast
    }

    static public function incrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromSession(); // ⬅️ aangepast
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::saveCartItemsToSession($cart_items); // ⬅️ aangepast
    }

    static public function decrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromSession(); // ⬅️ aangepast
        foreach($cart_items as $key => $item){
            if($item['product_id'] == $product_id && $item['quantity'] > 1){
                $cart_items[$key]['quantity']--;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::saveCartItemsToSession($cart_items); // ⬅️ aangepast
    }

    static public function calculateGrandTotal($items){
        return array_sum(array_column($items, 'total_amount'));
    }
}
