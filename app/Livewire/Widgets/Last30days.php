<?php

namespace App\Livewire\Widgets;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Collection;

class Last30days extends Component
{
    public $total = 0; // totaal verkochte producten in de laatste 30 dagen

    public function mount()
    {
        // Haal alle orders van de huidige user op van de laatste 30 dagen, inclusief hun items
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        // Verzamel alle order items uit deze bestellingen in één collection
        $items = collect();

        // Loop alle orders af, en voeg elk item uit die orders toe aan de verzameling $items
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $items->push($item); // elk item toevoegen aan de collection
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
