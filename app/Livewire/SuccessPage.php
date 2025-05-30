<?php

namespace App\Livewire;

use App\Models\Order;
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
    #[Url] //neemt de url van de pagina en zet deze in public $session_id
    public $session_id;

    public function render()
    {
        // haal de laatste bestelling op van de ingelogde user
        $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        if ($this->session_id) {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session_info = Session::retrieve($this->session_id);

            if ($session_info->payment_status != 'paid') {
                $latest_order->payment_status = 'failed';
                $latest_order->save();
                return redirect('/cancel');
            } elseif ($session_info->payment_status == 'paid') {
                $latest_order->payment_status = 'paid';

                // haal payment_intent op en bewaar het als transactie-ID
                $payment_intent_id = $session_info->payment_intent;
                $latest_order->transaction_id = $payment_intent_id;

                $latest_order->save();
            }
        }

        if (!$latest_order->transaction_id) {
            $latest_order->transaction_id = $payment_intent_id;
            $latest_order->save();

            // Genereer factuur PDF
            $pdf = Pdf::loadView('pdf.invoice', ['order' => $latest_order]);

            // Mail factuur
            Mail::to($latest_order->user->email)->send(new InvoiceMail($latest_order, $pdf->output()));
        }



        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
