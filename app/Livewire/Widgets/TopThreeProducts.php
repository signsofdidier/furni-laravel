<?php

namespace App\Livewire\Widgets;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Collection;

class TopThreeProducts extends Component
{
    public $topProducts = []; // De top 3 producten die deze user het meest heeft gekocht

    public function mount()
    {
        // Alle bestellingen van de huidige gebruiker ophalen, inclusief hun orderitems
        $orders = Order::with('items')->where('user_id', auth()->id())->get();

        // Een lege collectie om alle items te verzamelen
        $items = collect();

        // Elk order item van elke bestelling toevoegen aan $items
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $items->push($item); // voeg toe aan de lijst
            }
        }

        // Groepeer alles per product_id en tel hoeveel keer elk product gekocht is
        $grouped = $items
            ->groupBy('product_id') // groepeer alle items per product
            ->map(function ($items) {
                return $items->sum('quantity'); // tel het aantal verkochte stuks per product
            })
            ->sortDesc() // sorteer van meest naar minst verkocht
            ->take(3); // neem enkel de top 3

        // Haal voor de top 3 de productnamen op (voor de weergave)
        // mapWithKeys: geeft snel een “associatieve array” terug om te gebruiken in de view
        $this->topProducts = $grouped->mapWithKeys(function ($qty, $productId) {
            $product = Product::find($productId);
            $name = $product?->name ?? 'Unknown Product';
            return [$name => $qty];
        });
    }

    public function render()
    {
        return view('livewire.widgets.top-three-products');
    }
}
