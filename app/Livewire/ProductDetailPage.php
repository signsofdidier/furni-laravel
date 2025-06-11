<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - E-Commerce')]
class ProductDetailPage extends Component
{

    public $slug;
    public $quantity = 1;
    public $selectedColorId;

    public Product $product;

    public function mount($slug)
    {
        $this->product = Product::with(['colors', 'productColorStocks'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function increaseQuantity(){
        // dit zorgt ervoor dat de quantity niet groter kan worden dan de max stock
        if ($this->maxStock !== null && $this->quantity < $this->maxStock) {
            $this->quantity++;
        }
    }

    public function decreaseQuantity()
    {
        $this->quantity = max(1, $this->quantity - 1);
    }

    // add product to cart method
    public function addToCart($product_id){
        $this->quantity = max(1, $this->quantity); // Minimaal 1

        // MOET KLEUR SELECTEREN
        if (!$this->selectedColorId) {
            $this->addError('selectedColorId', 'Please select a color.');
            return;
        }

        // Controleer of we niet over de max stock gaan
        $inCart = CartManagement::getQuantityInCart($product_id, $this->selectedColorId); // Hoeveel in winkelwagen
        $maxStock = $this->maxStock; // Max stock voor geselecteerde kleur

        // dit zorgt ervoor dat de quantity niet groter kan worden dan de max stock
        if ($inCart + $this->quantity > $maxStock) {
            $remaining = $maxStock - $inCart;
            $itemText = $remaining === 1 ? 'item' : 'items';
            $this->addError('quantity', "Only $remaining $itemText left in stock for this color.");
            return;
        }

        // Voeg product met geselecteerde kleur toe aan cart
        $total_count = CartManagement::addItemToCartWithQuantity(
            $product_id,
            $this->quantity,
            $this->selectedColorId
        );

        // Reset quantity na toevoegen
        $this->quantity = 1;

        // Update cart count in Navbar via event
        $this->dispatch('update-cart-count', $total_count)
            ->to(Navbar::class);

        // Update cart in de drawer modal
        $this->dispatch('cart-updated');

        // LIVEWIRE SWEETALERT
        $this->dispatch('alert');

    }

    // JE MAG NIET BOVEN MAXIMALE STOCK AANVRAAGEN MET INCREASE QUANTITY
    public function getMaxStockProperty()
    {
        // als er geen kleur geselecteerd is ..
        if (!$this->selectedColorId) {
            return null;
        }

        return $this->product->stockForColorId($this->selectedColorId); // stock voor geselecteerde kleur
    }



    public function render()
    {
        // haal alleen de actieve, featured producten op (max. 8)
        $featuredProducts = Product::query()
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->take(8)
            ->get();

        return view('livewire.product-detail-page', [
            'product' => $this->product,
            //'product' => Product::where('slug', $this->slug)->firstOrFail(),
            'featuredProducts' => $featuredProducts
        ]);
    }
}
