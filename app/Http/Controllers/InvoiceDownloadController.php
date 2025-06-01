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
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Laad alle nodige relaties
        $order->load(['user', 'address', 'items.product', 'items.color']);

        $pdf = Pdf::loadView('pdf.order-placed', ['order' => $order]);
        return $pdf->download('invoice-order-' . $order->id . '.pdf');
    }

}
