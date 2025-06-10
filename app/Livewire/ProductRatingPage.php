<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductRatingPage extends Component
{
   public Product $product; // Product waar de gebruiker rating geeft
   public int $rating = 0; // Gebruiker heeft nog geen rating

   // De mount-methode wordt automatisch aangeroepen wanneer het component geladen wordt
   public function mount(Product $product){
        $this->product = $product;

        // Als de gebruiker ingelogd is, haal zijn rating op
       if (Auth::check()) {
           // haal op wat de huidige gebruiker al gestemd heeft, of 0 als hij nog niet gestemd heeft.
           $this->rating = $product->ratings()->where('user_id', Auth::id())->value('rating') ?? 0;
       }
   }


    // klik op een ster
    public function rate($value)
    {
        // Als de gebruiker niet ingelogd is, redirect naar login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // De score wordt in de database gezet met updateOrCreate()
        ProductRating::updateOrCreate(
            [
                'user_id' => Auth::id(), // Inlogde gebruiker
                'product_id' => $this->product->id, // Product
            ],
            ['rating' => $value] // Rating
        );

        $this->rating = $value; // Gebruiker heeft rating gegeven
        $this->dispatch('$refresh'); // Herlaad component
    }


    public function render()
    {
        return view('livewire.product-rating-page', [
            'average' => $this->product->averageRating(), // Gemiddelde rating
            'total' => $this->product->totalRatings(), // Totaal aantal ratings
        ]);
    }
}
