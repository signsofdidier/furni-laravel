<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMail extends Mailable
{
    // Met deze traits kan je mails op de queue zetten (dus asynchroon versturen) en blijven de model-gegevens (bv Order) correct bewaard als het via de queue gaat.
    use Queueable, SerializesModels;

    // Hierin zit het order dat we willen mailen
    public $order;

    // Als deze mail gemaakt wordt, geef je een order mee
    public function __construct(Order $order)
    {
        // Sla het order op als property
        $this->order = $order;

        // kleur en product laden
        $this->order = $order->load('items.color', 'items.product', 'user');
    }


    // PDF IN MAIL
    public function build()
    {
        // Maak de PDF aan via een blade view (pdf.invoice)
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $this->order]);

        // Bouw de mail op:
        // - subject = "Your Order No. X is confirmed"
        // - blade view = emails.invoice (inhoud van mail zelf)
        // - pdf als bijlage, met correcte naam en type
        return $this->subject('Your Order No. ' . $this->order->id . ' is confirmed')
            ->view('emails.invoice')
            ->attachData($pdf->output(), 'invoice-order-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}

