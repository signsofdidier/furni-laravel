<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\InvoiceMail;
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
    // Alle adressen van de user (voor keuze of nieuw adres invullen)
    public $addresses; // Alle adressen van de user
    public $selected_address_id; // Gekozen adres-id (of 'new' voor nieuw adres)

    // Nieuw adres velden
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;

    public $payment_method; // Stripe of cash on delivery (cod)

    public float $sub_total = 0; // Totale waarde van cart excl. shipping
    public float $shipping_amount = 0; // Verzendkosten (wordt berekend)
    public float $free_shipping_threshold = 0; // Treshold voor gratis verzending

    public function mount()
    {
        // Haal cart items op uit sessie, als die leeg is: redirect naar products
        $cart_items = CartManagement::getCartItemsFromSession();
        if(count($cart_items) == 0){
            return redirect('/products');
        }

        // Haal gratis shipping THRESHOLD op uit settings
        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0;

        // Bereken het sub-totaal van de cart
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);

        // Bereken verzendkosten
        $this->calculateShippingAmount($cart_items);

        // Haal user en zijn adressen op
        $user = auth()->user();
        if ($user) {
            $this->addresses = $user->addresses; // Haal alle adressen van de user op
            // Als er adressen zijn, selecteer standaard het eerste
            if ($this->addresses->count()) {
                $this->selected_address_id = $this->addresses->first()->id;
            } else {
                // Geen adressen? Default op 'nieuw adres'
                $this->selected_address_id = 'new';
            }
        }
    }

    /* MAAK ADDRES VELDEN LEEG wanneer user van adres verandert */
    // Wanneer user van adres verandert (select veld), maak de nieuwe adresvelden leeg
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

    // Wordt aangeroepen als user op "Place Order" drukt
    public function placeOrder()
    {
        $user = auth()->user();

        // 1. Eerst validatie: hangt af van of je een bestaand of nieuw adres kiest
        $rules = [
            'payment_method' => 'required',
        ];
        if ($this->selected_address_id == 'new' || !$this->addresses || !$this->addresses->count()) {
            // Nieuw adres vereist: extra velden invullen
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
            // Nieuw adres aanmaken en opslaan
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

        // Cart data opnieuw ophalen & bedragen updaten
        $cart_items = CartManagement::getCartItemsFromSession();
        $this->sub_total = CartManagement::calculateGrandTotal($cart_items);
        $this->calculateShippingAmount($cart_items);

        // 3. Order aanmaken afhankelijk van betaalmethode
        if($this->payment_method == 'cod'){
            return $this->createOrder($cart_items, $address_id);
        }

        if($this->payment_method == 'stripe'){
            // Stripe: orderdata opslaan in de sessie en user naar Stripe Checkout sturen
            session()->put('pending_order_data', [
                'cart_items'    => $cart_items,
                'address_id'    => $address_id,
                'sub_total'     => $this->sub_total,
                'shipping_amount' => $this->shipping_amount,
            ]);
            return $this->createStripeCheckout($cart_items);
        }
    }

    // Start Stripe Checkout sessie, geeft user door naar de betaalpagina van Stripe
    private function createStripeCheckout($cart_items)
    {
        $line_items = []; // Lege lijst om items in te voegen

        // Zet alle cart producten klaar in het juiste Stripe formaat
        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur', // In EUR
                    'unit_amount' => $item['unit_amount'] * 100, // In cents
                    'product_data' => [
                        'name' => $item['name'], // Product naam
                    ],
                ],
                'quantity' => $item['quantity'], // Hoeveelheid
            ];
        }

        // Voeg SHIPPINGCOST als aparte regel toe (indien > 0)
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

        // Stripe keys instellen en sessie aanmaken
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionCheckout = Session::create([
            'payment_method_types' => ['card'], // Betaalmethode: Card
            'customer_email' => auth()->user()->email, // Gebruikers email
            'line_items' => $line_items, // Items toevoegen
            'mode' => 'payment', // Betaalmethode: Payment
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}', // Na betalen naar deze route
            'cancel_url' => route('cancel'), // Redirect wanneer betaling geannuleerd
        ]);

        return redirect($sessionCheckout->url); // Stuur user naar Stripe om te betalen
    }

    /* MAAK NIEUW ORDER AAN VOOR CASH ON DELIVERY */
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
        $order->notes = 'Order placed by ' . $user->name;
        $order->save();

        // Voeg alle producten van de cart toe aan de order (in de order_items tabel)
        $order->items()->createMany($cart_items);

        // VOORAAD VERLAGEN per product + kleur
        foreach ($cart_items as $item) {

            $stockEntry = ProductColorStock::where('product_id', $item['product_id'])
                ->where('color_id', $item['color_id']) // Zoek de vooraad entry met de juiste kleur
                ->first(); // Zoek de eerste vooraad entry

            if ($stockEntry) {
                $stockEntry->decrement('stock', $item['quantity']);
            }
        }

        // Cart leegmaken na succesvolle bestelling
        CartManagement::clearCartItems();

        // Factuur e-mail versturen naar de user (enkel bij cash on delivery)
        if ($this->payment_method === 'cod' && $user) {
            Mail::to($order->user->email)->send(new InvoiceMail($order));
        }

        // User doorsturen naar success-pagina
        return redirect()->route('success');
    }

    public function render()
    {
        // Haal cart opnieuw op, update subtotalen en verzendkost (bv voor als user terugkomt van betalen)
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

    // shipping_amount berekenen en updaten
    private function calculateShippingAmount(array $cart_items): void
    {
        $this->shipping_amount = CartManagement::calculateShippingAmount($cart_items);
    }
}
