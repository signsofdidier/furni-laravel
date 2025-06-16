<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlacedMail;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductColorStock;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $addresses;               // Alle adressen van de user
    public $selected_address_id;     // Gekozen adres-id (of 'new' voor nieuw adres)
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;

    public $payment_method;

    public float $sub_total = 0;
    public float $shipping_amount = 0;
    public float $free_shipping_threshold = 0;

    public function mount()
    {
        $cart_items = CartManagement::getCartItemsFromSession();
        if(count($cart_items) == 0){
            return redirect('/products');
        }

        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0;

        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        $user = auth()->user();
        if ($user) {
            $this->addresses = $user->addresses; // Haal alle adressen van de user op
            // Optioneel: auto-selecteer het laatst gebruikte adres
            if ($this->addresses->count()) {
                $this->selected_address_id = $this->addresses->first()->id;
            } else {
                $this->selected_address_id = 'new';
            }
        }
    }

    public function updatedSelectedAddressId($value)
    {
        // Clear de nieuwe adresvelden als een bestaand adres gekozen wordt
        if ($value !== 'new') {
            $this->first_name = null;
            $this->last_name = null;
            $this->phone = null;
            $this->street_address = null;
            $this->city = null;
            $this->state = null;
            $this->zip_code = null;
        }
    }

    public function placeOrder()
    {
        $user = auth()->user();

        // 1. Valideer invoer afhankelijk van adreskeuze
        $rules = [
            'payment_method' => 'required',
        ];
        if ($this->selected_address_id == 'new' || !$this->addresses || !$this->addresses->count()) {
            $rules = array_merge($rules, [
                'first_name'      => 'required',
                'last_name'       => 'required',
                'phone'           => 'required',
                'street_address'  => 'required',
                'city'            => 'required',
                'state'           => 'required',
                'zip_code'        => 'required',
            ]);
        }
        $this->validate($rules);

        // 2. Bepaal het adres-id
        if ($this->selected_address_id && $this->selected_address_id !== 'new') {
            // Bestaand adres gekozen
            $address_id = $this->selected_address_id;
        } else {
            // Nieuw adres aanmaken
            $address = Address::create([
                'user_id'        => $user ? $user->id : null,
                'first_name'     => $this->first_name,
                'last_name'      => $this->last_name,
                'phone'          => $this->phone,
                'street_address' => $this->street_address,
                'city'           => $this->city,
                'state'          => $this->state,
                'zip_code'       => $this->zip_code,
            ]);
            $address_id = $address->id;
        }

        $cart_items = CartManagement::getCartItemsFromSession();
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        // 3. Order aanmaken afhankelijk van betaalmethode
        if($this->payment_method == 'cod'){
            return $this->createOrder($cart_items, $address_id);
        }

        if($this->payment_method == 'stripe'){
            // Zet alle orderdata en gekozen adres in sessie
            session()->put('pending_order_data', [
                'cart_items'    => $cart_items,
                'address_id'    => $address_id,
                'sub_total'     => $this->sub_total,
                'shipping_amount' => $this->shipping_amount,
            ]);
            return $this->createStripeCheckout($cart_items);
        }
    }

    private function createStripeCheckout($cart_items)
    {
        $line_items = [];

        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if ($this->shipping_amount > 0) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $this->shipping_amount * 100,
                    'product_data' => [
                        'name' => 'Shipping',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionCheckout = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => auth()->user()->email,
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return redirect($sessionCheckout->url);
    }

    private function createOrder($cart_items, $address_id)
    {
        $user = auth()->user();
        $order = new Order();
        $order->user_id = $user ? $user->id : null;
        $order->address_id = $address_id;
        $order->sub_total = $this->sub_total;
        $order->grand_total = $this->sub_total + $this->shipping_amount;
        $order->payment_method = $this->payment_method;
        $order->payment_status = $this->payment_method == 'cod' ? 'pending' : 'paid';
        $order->status = 'new';
        $order->currency = 'EUR';
        $order->shipping_amount = $this->shipping_amount;
        $order->shipping_method = 'Truck Delivery';
        $order->notes = 'Order placed by ' . ($user ? $user->name : 'guest');
        $order->save();

        // Sla order items op
        $order->items()->createMany($cart_items);

        // Verlaag stock
        foreach ($cart_items as $item) {
            $stockEntry = ProductColorStock::where('product_id', $item['product_id'])
                ->where('color_id', $item['color_id'])
                ->first();

            if ($stockEntry) {
                $stockEntry->decrement('stock', $item['quantity']);
            }
        }

        // Leeg cart alleen na succesvol order
        CartManagement::clearCartItems();

        // Stuur mail voor COD
        if ($this->payment_method === 'cod' && $user) {
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
        }

        return redirect()->route('success');
    }

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromSession();
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        return view('livewire.checkout-page', [
            'cart_items'           => $cart_items,
            'sub_total'            => $this->sub_total,
            'shipping_amount'      => $this->shipping_amount,
            'free_shipping_threshold' => $this->free_shipping_threshold,
            'grand_total'          => $this->sub_total + $this->shipping_amount,
            'addresses'            => $this->addresses,
            'selected_address_id'  => $this->selected_address_id,
        ]);
    }

    private function calculateShippingAmount(array $cart_items): void
    {
        $this->shipping_amount = CartManagement::calculateShippingAmount($cart_items);
    }
}
