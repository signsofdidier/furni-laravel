<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductRatingPage extends Component
{
   public Product $product; // Product waar de gebruiker rating geeft
   public int $rating = 0; // Gebruiker heeft nog geen rating

    public function render()
    {
        return view('livewire.product-rating-page', [
            'average' => $this->product->averageRating(), // Gemiddelde score via product relatie
            'total' => $this->product->reviews()->count(), // Totaal aantal reviews
        ]);
    }
}
