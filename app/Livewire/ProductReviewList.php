<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductReviewList extends Component
{
    public Product $product;

    // Wanneer een review wordt toegevoegd, REFRESH de component
    protected $listeners = ['reviewAdded' => '$refresh'];

    public function render()
    {
        return view('livewire.product-review-list', [
            'reviews' => $this->product->reviews()->where('approved', true)->latest()->with('user')->get(),
        ]);
    }
}
