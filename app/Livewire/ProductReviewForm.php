<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductReviewForm extends Component
{
    public Product $product;

    public int $rating = 0;
    public string $title = '';
    public string $body = '';

    public bool $showForm = false;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function showReviewForm()
    {
        if (! $this->hasUserReviewed()) {
            $this->showForm = true;
        }
    }

    public function save()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->hasUserReviewed()) {
            session()->flash('error', 'You have already submitted a review for this product.');
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:2000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'title' => $this->title,
            'body' => $this->body,
        ]);

        $this->reset(['rating', 'title', 'body', 'showForm']);

        session()->flash('success', 'Review created!');
        $this->dispatch('reviewAdded');
    }

    public function hasUserReviewed(): bool
    {
        return $this->product
            ->reviews()
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function render()
    {
        return view('livewire.product-review-form');
    }
}
