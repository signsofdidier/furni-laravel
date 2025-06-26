<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Blog;
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

    public $blogs; // Hier bewaar ik de blogs voor de homepage

    public function mount(){
        // Haal de free_shipping_threshold uit de database (één record in settings)
        $setting = Setting::first();
        $this->free_shipping_threshold = $setting->free_shipping_threshold ?? 0.0;
        $this->blogs = Blog::latest()->take(8)->get();
    }

    // ADD PRODUCT TO CART
    public function addToCart($product_id){
        // Voeg 1 stuk toe aan de cart (helper)
        $total_count = CartManagement::addItemToCart($product_id); //

        // Stuur event naar Navbar zodat de teller bijwerkt
        $this->dispatch('update-cart-count', total_count: $total_count)->to('partials.navbar');

        // Update cart in de drawer modal
        $this->dispatch('cart-updated');

        // LIVEWIRE SWEETALERT
        $this->dispatch('alert');
    }

    public function render()
    {
        // als de brand/categories actief is geven we ze weer (is_active staat in de brand model)
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();

        // haal alleen de actieve, featured producten op (max. 8)
        $featuredProducts = Product::query()
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(8)
            ->get();

        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts
        ]);
    }
}
