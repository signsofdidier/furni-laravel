<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use App\Models\Setting;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{

    public $total_count = 0;

    // Threshold voor gratis verzending uit de Settings
    public float $free_shipping_threshold  = 0;

    // Gratis verzending uitgeschakeld uit de Settings
    public bool $free_shipping_enabled = false;

    public function mount(){
        // telt alle items in de cart op maar ook de quantity van een item
        $this->total_count = array_sum(array_column(CartManagement::getCartItemsFromSession(), 'quantity'));

        // Haal de free_shipping_threshold uit de database (één record in settings)
        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0.0;

        // Zet free shipping enabled indien drempel > 0
        $this->free_shipping_enabled = $setting->free_shipping_enabled ?? false;
    }

    #[On('update-cart-count')]
    public function updateCartCount($total_count){
        $this->total_count = $total_count;
    }

    // refresh cart na toevoegen van product of aanpassing in de cart
    #[On('cart-updated')]
    public function refreshCount()
    {
        $this->total_count = array_sum(array_column(CartManagement::getCartItemsFromSession(), 'quantity'));
    }


    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
