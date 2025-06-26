<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Order Detail')]
class MyOrderDetailPage extends Component
{
    public $order_id;

    public function mount($order_id){
        $this->order_id = $order_id;
    }

    public function render()
    {
        // Haal de order zelf op (inclusief gekoppeld adres)
        $order = Order::with('address')->find($this->order_id);

        // Haal alle items van deze order op (inclusief product en kleur info)
        $order_items = OrderItem::with(['product', 'color'])->where('order_id', $this->order_id)->get();



        // Toon de order items, address en de order in de view
        return view('livewire.my-order-detail-page', [
            'order_items' => $order_items,
            'order' => $order,
            'address' => $order?->address,
        ]);
    }
}
