<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

// PDF-generatie en mail
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoicePaidMail;

#[Title('Success - E-Commerce')]
class SuccessPage extends Component
{
    // Deze property zal automatisch gevuld worden met ?session_id=xxx uit de URL
    #[Url]
    public $session_id;

    public function render()
    {
        // Haal de laatste bestelling op van de ingelogde gebruiker, inclusief adres en user-relatie
        $latest_order = Order::with('address', 'user')
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        // Als er een Stripe session_id in de URL aanwezig is, dan controleren we de betaling
        if ($this->session_id) {
            // Stel de Stripe API key in
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Haal informatie op over de Stripe Checkout Session
            $session_info = Session::retrieve($this->session_id);

            // Controleer of de betaling mislukt is
            if ($session_info->payment_status !== 'paid') {
                $latest_order->payment_status = 'failed';
                $latest_order->save();

                // Redirect naar een annuleringspagina
                return redirect('/cancel');
            }

            // Als de betaling geslaagd is:
            $latest_order->payment_status = 'paid';

            // Haal het payment_intent ID op en sla het op als transactie-ID
            $latest_order->transaction_id = $session_info->payment_intent;
            $latest_order->save();

            // Genereer een PDF-factuur met de bestelling
            $pdf = Pdf::loadView('pdf.invoice', [
                'order' => $latest_order
            ]);

            // Verstuur de factuur als bijlage via mail naar de gebruiker
            /*Mail::to($latest_order->user->email)
                ->send(new InvoicePaidMail($latest_order));*/
        }

        // Toon de success pagina met ordergegevens
        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
