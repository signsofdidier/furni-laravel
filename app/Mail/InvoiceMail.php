<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct(Order $order, $pdf)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Je factuur van bestelling #' . $this->order->id)
            ->view('emails.invoice')
            ->attachData($this->pdf, 'factuur.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
