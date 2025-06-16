<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\InvoicePaidMail;
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
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;
    public $discount_code;

    public float $sub_total = 0;
    public float $shipping_amount = 0;
    public float $free_shipping_threshold = 0;

    public function mount(){
        $cart_items = CartManagement::getCartItemsFromSession();
        if(count($cart_items) == 0){
            return redirect('/products');
        }

        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0;

        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        // AUTO-FILL adresgegevens indien beschikbaar
        $user = auth()->user();
        if ($user && $user->address) {
            $this->first_name = $user->address->first_name;
            $this->last_name = $user->address->last_name;
            $this->phone = $user->address->phone;
            $this->street_address = $user->address->street_address;
            $this->city = $user->address->city;
            $this->state = $user->address->state;
            $this->zip_code = $user->address->zip_code;
        }
    }

    public function placeOrder(){
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);

        $cart_items = CartManagement::getCartItemsFromSession();

        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        // Voor Cash on Delivery: maak direct het order aan
        if($this->payment_method == 'cod'){
            return $this->createOrder($cart_items);
        }

        // Voor Stripe: maak geen order aan, maar sla orderdata op in session
        // en stuur door naar Stripe
        if($this->payment_method == 'stripe'){
            return $this->createStripeCheckout($cart_items);
        }
    }

    private function createStripeCheckout($cart_items)
    {
        // Sla orderdata op in sessie (zodat we het later kunnen gebruiken)
        session()->put('pending_order_data', [
            'cart_items' => $cart_items,
            'address_data' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
            ],
            'sub_total' => $this->sub_total,
            'shipping_amount' => $this->shipping_amount,
        ]);

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

    private function createOrder($cart_items)
    {
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->sub_total = $this->sub_total;
        $order->grand_total = $this->sub_total + $this->shipping_amount;
        $order->payment_method = $this->payment_method;
        $order->payment_status = $this->payment_method == 'cod' ? 'pending' : 'paid';
        $order->status = 'new';
        $order->currency = 'EUR';
        $order->shipping_amount = $this->shipping_amount;
        $order->shipping_method = 'Flat Rate';
        $order->notes = 'Order placed by ' . auth()->user()->name;
        $order->save();

        // Maak het adres aan
        $address = new Address();
        $address->order_id = $order->id;
        $address->user_id = auth()->user()->id;
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;
        $address->save();

        // Sla als profieladres op
        $user = auth()->user();
        if (!$user->address) {
            $user->address()->associate($address);
            $user->save();
        }

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
        if ($this->payment_method === 'cod') {
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
            'cart_items' => $cart_items,
            'sub_total' => $this->sub_total,
            'shipping_amount' => $this->shipping_amount,
            'free_shipping_threshold' => $this->free_shipping_threshold,
            'grand_total' => $this->sub_total + $this->shipping_amount,
        ]);
    }

    private function calculateShippingAmount(array $cart_items): void
    {
        $this->shipping_amount = CartManagement::calculateShippingAmount($cart_items);
    }
}
