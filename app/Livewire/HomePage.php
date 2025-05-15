<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;

// Titel (tablat)
#[Title('Home Page - Ecommrce')]

class HomePage extends Component
{
    public function render()
    {
        // als de brand actief is geven we hem weer (is_active staat in de brand model)
        $brands = Brand::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();


        return view('livewire.home-page', [
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
