<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $this->order]);

        return $this->subject('Your Order #' . $this->order->id . ' is confirmed')
            ->view('emails.invoice-paid')
            ->attachData($pdf->output(), 'invoice-order-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}

