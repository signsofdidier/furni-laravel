<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductRatingPage extends Component
{
   public Product $product; // Product waar de gebruiker rating geeft
   public int $rating = 0; // Gebruiker heeft nog geen rating

    protected $listeners = ['reviewAdded' => '$refresh']; // Wanneer een review is toegevoegd, refresh de pagina

    public function render()
    {
        $average = round($this->product->reviews()->avg('rating'), 1);
        $total = $this->product->reviews()->count();

        return view('livewire.product-rating-page', compact('average', 'total'));
    }

}
