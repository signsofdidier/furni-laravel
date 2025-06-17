<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;

        // kleur en product laden
        $this->order = $order->load('items.color', 'items.product', 'user');
    }


    // PDF IN MAIL
    public function build()
    {
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $this->order]);

        // Stuurt een e-mail met een pdf attachement
        return $this->subject('Your Order No. ' . $this->order->id . ' is confirmed')
            ->view('emails.invoice')
            ->attachData($pdf->output(), 'invoice-order-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}

