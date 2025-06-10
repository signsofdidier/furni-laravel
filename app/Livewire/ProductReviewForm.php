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
    public bool $editing = false;

    protected $listeners = ['editReview' => 'enableEdit'];

    public function mount(Product $product)
    {
        $this->product = $product;

        if (Auth::check()) {
            $existingReview = $this->product->reviews()->where('user_id', Auth::id())->first();
            if ($existingReview) {
                $this->rating = $existingReview->rating;
                $this->title = $existingReview->title;
                $this->body = $existingReview->body;
            }
        }
    }

    public function showReviewForm()
    {
        $this->showForm = true;
        $this->editing = false;
    }

    public function enableEdit()
    {
        $this->showForm = true;
        $this->editing = true;

        if (Auth::check()) {
            $review = $this->product->reviews()->where('user_id', Auth::id())->first();
            if ($review) {
                $this->rating = $review->rating;
                $this->title = $review->title;
                $this->body = $review->body;
            }
        }
    }

    public function save()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:2000',
        ]);

        Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $this->product->id,
            ],
            [
                'rating' => $this->rating,
                'title' => $this->title,
                'body' => $this->body,
            ]
        );

        $this->reset(['rating', 'title', 'body', 'showForm', 'editing']);

        session()->flash('success', 'Review created!');
        $this->dispatch('reviewAdded');
    }

    public function render()
    {
        return view('livewire.product-review-form');
    }
}
