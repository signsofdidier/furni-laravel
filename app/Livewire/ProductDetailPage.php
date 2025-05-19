<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - E-Commerce')]
class ProductDetailPage extends Component
{

    public $slug;

    public $quantity = 1;

    public function increaseQuantity(){
        $this->quantity++;
    }

    public function decreaseQuantity(){
        if($this->quantity > 1){
            $this->quantity--;
        }
    }

    // add product to cart method
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQuantity($product_id, $this->quantity);

        //Hiermee kan je in de navbar class de 'update-cart-count' event triggeren met #[On('update-cart-count')]
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        LivewireAlert::title('Product added to cart')
            ->success()
            ->position('bottom-end')
            ->timer(3000)
            ->toast()
            ->show();

    }

    public function mount($slug){
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail()
        ]);
    }
}
