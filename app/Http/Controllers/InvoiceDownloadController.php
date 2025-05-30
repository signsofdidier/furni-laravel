<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceDownloadController extends Controller
{
    public function __invoke(Order $order)
    {
        // Zorg dat gebruikers alleen hun eigen orders kunnen downloaden
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.invoice', ['order' => $order]);

        return $pdf->download('factuur-order-' . $order->id . '.pdf');
    }
}
