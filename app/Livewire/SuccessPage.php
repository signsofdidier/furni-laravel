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

// PDF-generatie en mail
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

#[Title('Success - E-Commerce')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {
        $order = null;

        // Als er een Stripe session_id in de URL is, maak dan het order aan
        if ($this->session_id) {
            $order = $this->handleStripeSuccess();
        } else {
            // Voor COD orders, haal de laatste order op
            $order = Order::with('address', 'user')
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->first();
        }

        return view('livewire.success-page', [
            'order' => $order,
        ]);
    }

    private function handleStripeSuccess()
    {
        // Controleer of er pending order data in de sessie staat
        $pending_order_data = session()->get('pending_order_data');
        if (!$pending_order_data) {
            // Geen pending data, probeer laatste order op te halen
            return Order::with('address', 'user')
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->first();
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session_info = Session::retrieve($this->session_id);

        // Controleer of de betaling mislukt is
        if ($session_info->payment_status !== 'paid') {
            // Verwijder pending order data en redirect naar cancel
            session()->forget('pending_order_data');
            return redirect('/cancel');
        }

        // Betaling geslaagd - maak nu het order aan
        $order = $this->createOrderFromPendingData($pending_order_data, $session_info);

        // Verwijder pending order data uit sessie
        session()->forget('pending_order_data');

        return $order;
    }

    private function createOrderFromPendingData($pending_order_data, $session_info)
    {
        $cart_items = $pending_order_data['cart_items'];
        $address_id = $pending_order_data['address_id']; // ADRES ID UIT DE SESSIE!

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
        $order->transaction_id = $session_info->payment_intent;
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

        // Leeg cart nu
        CartManagement::clearCartItems();

        // Verstuur factuur mail
        Mail::to($order->user->email)->send(new InvoiceMail($order));

        // Herlaad order met relaties voor de view
        return Order::with('address', 'user')->find($order->id);
    }

}
