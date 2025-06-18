<?php

namespace App\Livewire\Widgets;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Collection;

class TopThreeProducts extends Component
{
    public $topProducts = [];

    public function mount()
    {
        // Alle bestellingen van de huidige gebruiker ophalen, inclusief hun orderitems
        $orders = Order::with('items')->where('user_id', auth()->id())->get();

        // Een lege collectie om alle items te verzamelen
        $items = collect();

        // Alle items uit alle bestellingen verzamelen in één lijst
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $items->push($item);
            }
        }

        // Items groeperen per product_id en optellen hoeveel keer elk product is gekocht
        $grouped = $items
            ->groupBy('product_id') // groepeer alle items per product
            ->map(function ($items) {
                return $items->sum('quantity'); // tel het aantal verkochte stuks per product
            })
            ->sortDesc() // sorteer van meest naar minst verkocht
            ->take(3); // neem enkel de top 3

        // Productnamen ophalen bij de product_id's en de top 3 in een associatieve array stoppen
        $this->topProducts = $grouped->mapWithKeys(function ($qty, $productId) {
            $product = Product::find($productId);
            $name = $product?->name ?? 'Onbekend product';
            return [$name => $qty];
        });
    }

    public function render()
    {
        return view('livewire.widgets.top-three-products');
    }
}
