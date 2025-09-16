<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Address;
use App\Models\Order;
use App\Models\ProductColorStock;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

#[Title('Success - E-Commerce')]
class SuccessPage extends Component
{
    // Stripe session_id uit de URL halen
    #[Url]
    public $session_id;

    public function render()
    {
        $order = null;

        // Als er een Stripe session_id in de URL is, maak dan het order aan
        if ($this->session_id) {
            $order = $this->handleStripeSuccess(); // Handle Stripe success
        } else {
            // Voor COD orders, haal de laatste order op
            $order = Order::with('address', 'user')
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->first();
        }

        // Toon de success view met het order erbij
        return view('livewire.success-page', [
            'order' => $order,
        ]);
    }

    // Deze functie wordt enkel gebruikt als de user via Stripe BETAALD heeft
    private function handleStripeSuccess()
    {
        // Haal pending order data uit de sessie (werd bewaard voor het doorsturen naar Stripe)
        $pending_order_data = session()->get('pending_order_data');
        if (!$pending_order_data) {
            // GEEN pending data? (bv. refresh of terugknop), geef gewoon laatste order terug
            return Order::with('address', 'user')
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->first();
        }

        // Zet Stripe key en HAAL INFO OP over deze sessie
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session_info = Session::retrieve($this->session_id);

        // Check of betaling NIET GESLAAGD is
        if ($session_info->payment_status !== 'paid') {
            // Alles wegsmijten en user naar cancel-pagina sturen
            session()->forget('pending_order_data');
            return redirect('/cancel');
        }

        // BETALING OK: nu order echt aanmaken in de database
        $order = $this->createOrderFromPendingData($pending_order_data, $session_info);

        // Pending order data mag nu uit de sessie
        session()->forget('pending_order_data');

        return $order;
    }

    // Zet de tijdelijke (pending) order om naar een echte order in de database
    private function createOrderFromPendingData($pending_order_data, $session_info)
    {
        $cart_items = $pending_order_data['cart_items'];
        $address_id = $pending_order_data['address_id']; // ADRES ID UIT DE SESSIE!

        // Maak een nieuwe Order aan
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->address_id = $address_id; // HIER KOPPEL IK HET JUISTE ADRES
        $order->sub_total = $pending_order_data['sub_total'];
        $order->grand_total = $pending_order_data['sub_total'] + $pending_order_data['shipping_amount'];
        $order->payment_method = 'bancontact';
        $order->payment_status = 'paid';
        $order->status = 'new';
        $order->currency = 'EUR';
        $order->shipping_amount = $pending_order_data['shipping_amount'];
        $order->shipping_method = 'Truck Delivery';
        $order->notes = 'Order placed by ' . auth()->user()->name;
        $order->transaction_id = $session_info->payment_intent; // Stripe transactie-ID
        $order->save();

        // Sla order items op
        $order->items()->createMany($cart_items);

        // VERLAAG STOCK per product + kleur
        foreach ($cart_items as $item) {
            $stockEntry = ProductColorStock::where('product_id', $item['product_id'])
                ->where('color_id', $item['color_id'])
                ->first();

            if ($stockEntry) {
                $stockEntry->decrement('stock', $item['quantity']);
            }
        }

        // Leeg cart nu
        CartManagement::clearCartItems();

        // Verstuur factuur mail
        Mail::to($order->user->email)->send(new InvoiceMail($order));

        // Return het order met relaties (adres & user)
        return Order::with('address', 'user')->find($order->id);
    }

}
