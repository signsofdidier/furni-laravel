<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // PDF IN MAIL
    public function build()
    {
        $pdf = Pdf::loadView('pdf.order-placed', ['order' => $this->order]);

        // Stuurt een e-mail met een pdf attachement
        return $this->subject('Your Order #' . $this->order->id . ' was placed successfully')
            ->view('emails.order-placed')
            ->attachData($pdf->output(), 'order-confirmation-' . $this->order->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
