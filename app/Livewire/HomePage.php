<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Attributes\Title;
use Livewire\Component;

// Titel (tablat)
#[Title('Home Page - E-commerce')]

class HomePage extends Component
{

    // Threshold voor gratis verzending uit de Settings
    public float $free_shipping_threshold  = 0;


    public function mount(){
        // Haal de free_shipping_threshold uit de database (één record in settings)
        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0.0;

    }

    public function render()
    {
        // als de brand actief is geven we hem weer (is_active staat in de brand model)
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();

        // haal alleen de actieve, featured producten op (max. 8)
        $featuredProducts = Product::query()
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->take(8)
            ->get();

        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts
        ]);


    }
}
