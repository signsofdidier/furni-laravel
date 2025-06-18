<?php

namespace App\Livewire\Widgets;

use App\Models\Order;
use Livewire\Component;

class TotalProductsWidget extends Component
{
    public $total = 0; // Totaal aantal gekochte producten

    public function mount()
    {
        // Haal alle bestellingen op van de ingelogde gebruiker, met hun items
        $orders = Order::with('items')->where('user_id', auth()->id())->get();

        // Verzamel alle items uit alle bestellingen
        $items = collect();
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $items->push($item);
            }
        }

        // Tel het totaal aantal gekochte producten (op basis van 'quantity')
        $this->total = $items->sum('quantity');
    }

    public function render()
    {
        // Toon de bijhorende widget-view
        return view('livewire.widgets.total-products-widget');
    }
}
