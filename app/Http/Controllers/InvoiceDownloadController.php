<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceDownloadController extends Controller
{

    // Wordt aangeroepen als iemand een factuur wil downloaden (1 order)
    public function __invoke(Order $order)
    {
        // Enkel je eigen orders kunnen downloaden, anders 403 forbidden
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Kies juiste view op basis van betaalmethode
        $view = $order->payment_method === 'cod' ? 'pdf.order-placed' : 'pdf.invoice';

        // Maak pdf aan met DomPDF, stuur order data mee naar de view
        $pdf = Pdf::loadView($view, ['order' => $order]);

        // Downloaden met een duidelijke bestandsnaam
        return $pdf->download('Invoice-order-' . $order->id . '.pdf');
    }

}
