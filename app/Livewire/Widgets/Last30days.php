<?php

namespace App\Livewire\Widgets;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Collection;

class Last30days extends Component
{
    public $total = 0;

    public function mount()
    {
        // Haal alle bestellingen op van de huidige gebruiker die in de laatste 30 dagen zijn geplaatst,
        // inclusief de bijbehorende orderitems
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        // Verzamel alle items uit deze bestellingen
        $items = collect();

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $items->push($item);
            }
        }

        // Tel het totale aantal verkochte producten in de laatste 30 dagen
        $this->total = $items->sum('quantity');
    }

    public function render()
    {
        return view('livewire.widgets.last30days');
    }
}
