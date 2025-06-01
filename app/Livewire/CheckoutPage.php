<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\InvoicePaidMail;
use App\Mail\OrderPlacedMail;
use App\Models\Address;
use App\Models\Order;
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

    // Threshold voor gratis verzending uit de Settings
    public float $free_shipping_threshold  = 0;

    // als de cart leeg is mag je niet je geen toegang hebben tot de checkout page en keer je terug naar products.
    public function mount(){
        $cart_items = CartManagement::getCartItemsFromSession();
        if(count($cart_items) == 0){
            return redirect('/products');
        }

        // Haal de threshold op en bewaar in een property
        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0;

        // Bereken op mount meteen de sub_total en shipping_amount
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);
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

        // Herbereken sub_total en shipping_amount voor het geval de cart ondertussen is aangepast
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        // Maak de line_items voor Stripe
        $line_items = [];
        foreach($cart_items as $item){
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

        // Maak het Order-object aan
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->grand_total = $this->sub_total;
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'EUR';

        // Sla verzendkosten op in het order (wordt later in database bewaard)
        $order->shipping_amount = $this->shipping_amount;
        $order->shipping_method = 'Flat Rate';
        $order->notes = 'Order placed by ' . auth()->user()->name;

        // Maak het Address-object aan
        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;

        $redirect_url = '';


        // Kies tussen Cash on Delivery of Stripe
        if($this->payment_method == 'stripe'){
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => auth()->user()->email,
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);

            // Sla de Stripe Transaction ID op in het order
            $order->transaction_id = $sessionCheckout->id;
            $redirect_url = $sessionCheckout->url;

        }else {
            $redirect_url = route('success');
        }

        $order->save();

        // Koppel het adres aan het nieuw aangemaakte order
        $address->order_id = $order->id;
        $address->save();

        // Sla alle item‐regels op in de “order_items”‐relatie (reflecteert wat in sessie zat)
        $order->items()->createMany($cart_items);

        // Na het plaatsen van de order wordt de cart geleegd
        CartManagement::clearCartItems();

        // Bij COD handmatig mail verzenden
        if ($this->payment_method === 'cod') {
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
        }

        return redirect($redirect_url);
    }


    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromSession();

        // Herbereken sub_total en shipping_amount in render (voor de weergave)
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'sub_total' => $this->sub_total,
            'shipping_amount' => $this->shipping_amount,
            'free_shipping_threshold' => $this->free_shipping_threshold,
            // $grand_total is sub_total + shipping (maar ik kan da ook in de view zelf optellen)
            'grand_total' => $this->sub_total + $this->shipping_amount,
        ]);
    }

    // Hulpmethode om verzendkosten te berekenen en in $shipping_amount te zetten
    private function calculateShippingAmount(array $cart_items): void
    {
        $totalShipping = 0;
        $grand_total = CartManagement::calculateGrandTotal($cart_items);

        // Haal uit de database de threshold (gratis verzending)
        $setting   = Setting::first();
        $threshold = $setting->free_shipping_threshold ?? 0;

        // Tel eerst de “normale” shipping voor alle items bij elkaar
        foreach ($cart_items as $item) {
            $totalShipping += ($item['shipping_cost'] * $item['quantity']);
        }

        // Alleen gratis verzending als threshold > 0 én grand_total ≥ threshold
        if ($threshold > 0 && $grand_total >= $threshold) {
            $totalShipping = 0;
        }

        $this->shipping_amount = $totalShipping;
    }
}
