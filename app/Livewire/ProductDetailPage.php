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
    // De slug uit de URL (om het juiste product op te halen)
    public $slug;

    // Hoeveelheid die je wil toevoegen
    public $quantity = 1;

    // Welke kleur geselecteerd?
    public $selectedColorId;

    public Product $product; // Het huidige product

    public function mount($slug)
    {
        // Haal het product (en alle kleuren + stock) op bij laden pagina
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

        // anders verhoog quantity
        $this->quantity++;
    }

    // Verlaag de quantity, maar nooit lager dan 1
    public function decreaseQuantity()
    {
        $this->quantity = max(1, $this->quantity - 1);
    }

    // ADD PRODUCT TO CART
    public function addToCart($product_id){
        $this->quantity = max(1, $this->quantity); // Minimaal 1, mag nooit lager dan 1

        // MOET EERST KLEUR KIEZEN
        if (!$this->selectedColorId) {
            $this->addError('selectedColorId', 'Please select a color first.');
            return;
        }


        // CONTROLE NIET OVER MAX STOCK GAAN
        // Hoeveel in winkelwagen
        $inCart = CartManagement::getQuantityInCart($product_id, $this->selectedColorId);
        $maxStock = $this->maxStock; // Max stock voor geselecteerde kleur

        // QUANTITY KAN NIET GROTER WORDEN DAN MAX STOCK
        if ($inCart + $this->quantity > $maxStock) {
            $remaining = $maxStock - $inCart;
            // Enkelvoud/meervoud voor het woordje 'item' (1 item vs. meerdere items)
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

        // Zet quantity terug op 1 na toevoegen
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
        // als er nog geen kleur gekozen is, dan geen voorraad checken
        if (!$this->selectedColorId) {
            return null;
        }

        // Geef de stock terug voor deze kleur (helper van Product model)
        return $this->product->stockForColorId($this->selectedColorId);
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

        // QUANTITY MAG NOOIT ONDER 1 GAAN
        return $this->quantity > 1;
    }

    // RESET QUANTITY BIJ KLEUR WISSEL
    // Als je van kleur wisselt, zet de quantity terug op 1 en reset errors
    public function updatedSelectedColorId($value)
    {
        // Reset de hoeveelheid naar 1 bij elke kleurwissel
        $this->quantity = 1;

        // Wis eventuele validatiefouten van vorige acties
        $this->resetErrorBag('quantity');
        $this->resetErrorBag('selectedColorId');
    }


    // Render de pagina, met product, 8 featured producten & info voor knoppen
    public function render()
    {
        /*logger()->info('Livewire debug', [
            'selectedColorId' => $this->selectedColorId,
            'quantity' => $this->quantity,
            'maxStock' => $this->maxStock,
        ]);*/

        // haal alleen de actieve, featured producten op (max. 8)
        $featuredProducts = Product::query()
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->take(8)
            ->get();

        return view('livewire.product-detail-page', [
            'product' => $this->product,
            'featuredProducts' => $featuredProducts,
            'canIncrease' => $this->canIncrease,
            'canDecrease' => $this->canDecrease,
        ]);
    }
}
