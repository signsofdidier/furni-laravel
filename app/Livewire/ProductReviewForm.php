<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductReviewForm extends Component
{
    public Product $product; // Het product waarvoor de review is

    // Formulier velden
    public int $rating = 0;
    public string $title = '';
    public string $body = '';

    // Toon of verberg het formulier (voor modal/uitklappen)
    public bool $showForm = false;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    // Laat formulier enkel zien als user nog geen review schreef
    public function showReviewForm()
    {
        if (! $this->hasUserReviewed()) {
            $this->showForm = true;
        }
    }

    public function save()
    {
        // MOET INGELOGD ZIJN OM REVIEW TE KUNNEN SCHRIJVEN
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Heeft deze user al een review op dit product?
        if ($this->hasUserReviewed()) {
            session()->flash('error', 'You have already submitted a review for this product.');
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:2000',
        ]);

        // Nieuwe review aanmaken, maar eerst goedkeuring admin nodig (approved = false)
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'title' => $this->title,
            'body' => $this->body,
            'approved' => false,
        ]);

        // Na opslaan: reset de velden & formulier sluiten
        $this->reset(['rating', 'title', 'body', 'showForm']);

        //session()->flash('success', 'Review submitted and is pending approval.');

        // Verstuur event zodat bv. het review-lijstje zich kan updaten
        $this->dispatch('reviewAdded');
    }

    // HEEFT DE USER AL EEN REVIEW OP DIT PRODUCT? BOOLEAN
    public function hasUserReviewed(): bool
    {
        return $this->product
            ->reviews()
            ->where('user_id', Auth::id())
            ->exists();
    }

    // REVIEW: USER HEEFT PRODUCT GEKOCHT
    public function getCanReviewProperty()
    {
        return auth()->check() && auth()->user()->hasPurchasedProduct($this->product->id);
    }

    public function render()
    {
        return view('livewire.product-review-form', [
            'canReview' => $this->canReview,
        ]);
    }
}
