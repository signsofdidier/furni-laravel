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
        if ($this->maxStock !== null && $this->quantity >= $this->maxStock) {
            $this->addError('quantity', 'Max stock reached for selected color.');
            return;
        }

        $this->quantity++;
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


        // CONTROLE NIET OVER MAX STOCK GAAN
        // Hoeveel in winkelwagen
        $inCart = CartManagement::getQuantityInCart($product_id, $this->selectedColorId);
        $maxStock = $this->maxStock; // Max stock voor geselecteerde kleur

        // QUANTITY KAN NIET GROTER WORDEN DAN MAX STOCK
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

    // JE MAG NIET BOVEN MAXIMALE STOCK AANVRAGEN MET INCREASE QUANTITY
    public function getMaxStockProperty()
    {
        // als er geen kleur geselecteerd is ..
        if (!$this->selectedColorId) {
            return null;
        }

        return $this->product->stockForColorId($this->selectedColorId); // stock voor geselecteerde kleur
    }



    /* INCREASE EN DECREASE KNOP BLOKKEREN */
    // MAG VERHOGEN ?
    public function getCanIncreaseProperty(): bool
    {
        // Als er nog geen kleur gekozen is, mag je niet verhogen
        if (!$this->selectedColorId) {
            return false;
        }

        // Als de maximale voorraad is bereikt voor de gekozen kleur, blokkeer verhogen
        if ($this->maxStock !== null && $this->quantity >= $this->maxStock) {
            return false;
        }

        // Anders is verhogen toegestaan
        return true;
    }

    // Bepaalt of de gebruiker de quantity mag verlagen
    public function getCanDecreaseProperty(): bool
    {
        // Als er geen kleur gekozen is, mag je niet verlagen
        if (!$this->selectedColorId) {
            return false;
        }

        // Quantity mag nooit onder 1 gaan
        return $this->quantity > 1;
    }

    // RESET QUANTITY BIJ KLEUR WISSEL
    // Wordt automatisch aangeroepen zodra de geselecteerde kleur wijzigt
    public function updatedSelectedColorId($value)
    {
        // Reset de hoeveelheid naar 1 bij elke kleurwissel
        $this->quantity = 1;

        // Wis eventuele validatiefouten van vorige acties
        $this->resetErrorBag('quantity');
        $this->resetErrorBag('selectedColorId');
    }




    public function render()
    {
        logger()->info('Livewire debug', [
            'selectedColorId' => $this->selectedColorId,
            'quantity' => $this->quantity,
            'maxStock' => $this->maxStock,
        ]);

        // haal alleen de actieve, featured producten op (max. 8)
        $featuredProducts = Product::query()
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->take(8)
            ->get();

        return view('livewire.product-detail-page', [
            'product' => $this->product,
            //'product' => Product::where('slug', $this->slug)->firstOrFail(),
            'featuredProducts' => $featuredProducts,
            'canIncrease' => $this->canIncrease,
            'canDecrease' => $this->canDecrease,
        ]);
    }
}
