<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - E-Commerce')]
class ProductsPage extends Component
{

    // moet worden gebruikt om de pagination te laten werken in livewire
    use WithPagination;

    // CATEGORY FILTER
    #[Url] // Deze eigenschap wordt gesynchroniseerd met de URL, zodat filters via de URL gedeeld kunnen worden
    public $selected_categories = []; // deze naam komt van de wire:model.live="selected_categories" uit products page

    // BRANDS FILTER
    #[Url] // Deze eigenschap wordt gesynchroniseerd met de URL, zodat filters via de URL gedeeld kunnen worden
    public $selected_brands = [];

    // COLORS FILTER
    #[Url]
    public $selected_colors = [];

    // FEATURED FILTER
    #[Url]
    public $featured;

    // ON SALE FILTER
    #[Url]
    public $on_sale;

    // IN STOCK FILTER
    #[Url]
    public $in_stock;

    // PRICE RANGE FILTER
    #[Url]
    public $price_range = 0; // Zet dit op 0 zodat de filter niet actief blijft als je via bvb homepage categories filtert

    // SORT BY FILTER
    #[Url]
    public $sort = 'latest';

    // selecter kleur voor addtocart
    public $selectedColorPerProduct = [];



    // add product to cart method
    public function addToCart($product_id){

        // KLEUR ADD TO CART

        // HAAL GESELECTEERD KLEUR OP
        $selectedColorId = $this->selectedColorPerProduct[$product_id] ?? null;


        // Als er geen kleur geselecteerd is, voeg een foutmelding toe
        if (!$selectedColorId) {

            $this->addError("selectedColorPerProduct.$product_id", 'Please select a color first.');
            return;
        }

        // CONTROLLEER NIET OVER MAX STOCK GAAN

        // Haal het product op met alle kleuren
        $product = Product::with('productColorStocks')->findOrFail($product_id);
        $maxStock = $product->stockForColorId($selectedColorId); // Max stock voor geselecteerde kleur
        $inCart = CartManagement::getQuantityInCart($product_id, $selectedColorId); // Hoeveel in winkelwagen

        // CHECK QUANTITY KAN NIET GROTER ZIJN DAN STOCK
        if ($inCart + 1 > $maxStock) // 1 = standaard quantity
        {
            $remaining = $maxStock - $inCart; // Berekening hoeveel overblijft
            $this->addError("selectedColorPerProduct.$product_id", "Only $remaining item(s) left in stock for this color.");
            return;
        }

        // Voeg product met geselecteerde kleur toe aan cart
        $total_count = CartManagement::addItemToCartWithQuantity(
            $product_id,  1, // standaard quantity op overzichtspagina
            $selectedColorId // Geef de geselecteerde kleur
        );

        //Hiermee kan je in de navbar class de 'update-cart-count' event triggeren met #[On('update-cart-count')]
        /*$this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);*/
        $this->dispatch('update-cart-count', total_count: $total_count)->to('partials.navbar');

        // Update cart in de drawer modal
        $this->dispatch('cart-updated');

        // LIVEWIRE SWEETALERT
        $this->dispatch('alert');

        // Reset gekozen kleur indien gewenst
        unset($this->selectedColorPerProduct[$product_id]);
    }


    public function render()
    {
        // Start een query om alleen actieve producten op te halen en hun stock quantity
        $productQuery = Product::with(['colors', 'productColorStocks'])
            ->where('is_active', 1);

        // Filter de producten op basis van de geselecteerde categorieën, als er categorieën zijn geselecteerd
        if(!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        // Selected COLORS filter
        if (!empty($this->selected_colors)) {
            $productQuery->whereHas('colors', function($query) {
                $query->whereIn('colors.id', $this->selected_colors);
            });
        }

        // BRANDS filter
        if(!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        // FEATURES Product filter
        if($this->featured) {
            $productQuery->where('is_featured', 1);
        }

        // ON SALE filter
        if($this->on_sale){
            $productQuery->where('on_sale', 1);
        }

        // IN STOCK filter
        if($this->in_stock) {
            $productQuery->whereHas('productColorStocks', function($query) {
                $query->where('stock', '>', 0);
            });
        }

        // PRICE RANGE filter
        if($this->price_range) {
            // Filter op prijs tussen 0 en de geselecteerde prijs
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }

        // Sorteer de LAATSTE toegevoegde producten
        if($this->sort == 'latest'){
            $productQuery->latest();
        }

        // Sorteer de producten op prijs van LAAG NAAR HOOG
        if($this->sort == 'lowest_price'){
            $productQuery->orderBy('price', 'asc');
        }

        // Sorteer de producten op prijs van HOOG NAAR LAAG
        if($this->sort == 'highest_price'){
            $productQuery->orderBy('price', 'desc');
        }

        // TEL de producten op basis van de geselecteerde categorieën
        $totalFilteredCount = (clone $productQuery)->count();

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(6),
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
            'colors' => Color::all(),
            'filtered_count' => $totalFilteredCount,
        ]);
    }
}
