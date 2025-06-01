<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceDownloadController extends Controller
{

    // Download de juiste invoice pdf bij orders, cash on delivery of stripe
    public function __invoke(Order $order)
    {
        // Enkel je eigen orders downloaden
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check betaalmethode
        $view = $order->payment_method === 'cod'
            ? 'pdf.order-placed'
            : 'pdf.invoice';

        $pdf = Pdf::loadView($view, ['order' => $order]);

        return $pdf->download('invoice-order-' . $order->id . '.pdf');
    }

}
