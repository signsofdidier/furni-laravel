<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductRatingPage extends Component
{
   // Product waar de gebruiker rating geeft
   public Product $product;

   // Gebruiker heeft nog geen rating
   public int $rating = 0;

    // Wanneer een review is toegevoegd, refresh de pagina
    protected $listeners = ['reviewAdded' => '$refresh'];

    public function render()
    {
        // Gemiddelde score via product relatie
        $average = round($this->product->reviews()->where('approved', true)->avg('rating'), 1);

        // Totaal aantal reviews
        $total = $this->product->reviews()->where('approved', true)->count();

        return view('livewire.product-rating-page', compact('average', 'total'));
    }

}
