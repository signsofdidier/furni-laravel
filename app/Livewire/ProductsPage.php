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

    #[Url] // Deze eigenschap wordt gesynchroniseerd met de URL, zodat filters via de URL gedeeld kunnen worden
    public $selected_categories = []; // deze naam komt van de wire:model.live="selected_categories" uit products page

    #[Url] // Deze eigenschap wordt gesynchroniseerd met de URL, zodat filters via de URL gedeeld kunnen worden
    public $selected_brands = [];

    #[Url]
    public $selected_colors = [];

    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $in_stock;

    #[Url]
    public $price_range = 0; // Zet dit op 0 zodat de filter niet actief blijft als je via bvb homepage categories filtert

    // sort by
    #[Url]
    public $sort = 'latest';


    // add product to cart method
    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);

        //Hiermee kan je in de navbar class de 'update-cart-count' event triggeren met #[On('update-cart-count')]
        /*$this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);*/
        $this->dispatch('update-cart-count', total_count: $total_count)->to('partials.navbar');

        // Update cart in de drawer modal
        $this->dispatch('cart-updated');

        // LIVEWIRE SWEETALERT
        $this->dispatch('alert');

        /*$this->dispatch('alert',
            type: 'success',
            title: 'Product added to cart',
            position: 'bottom-end',
            timer: 3000,
            toast: true
        );*/

    }

    public function render()
    {
        // Start een query om alleen actieve producten op te halen
        $productQuery = Product::query()->where('is_active', 1);


        // Filter de producten op basis van de geselecteerde categorieën, als er categorieën zijn geselecteerd
        if(!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if (!empty($this->selected_colors)) {
            $productQuery->whereHas('colors', function($query) {
                $query->whereIn('colors.id', $this->selected_colors);
            });
        }

        // Brand filter
        if(!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        // Features Product filter
        if($this->featured) {
            $productQuery->where('is_featured', 1);
        }

        // On Sale filter
        if($this->on_sale){
            $productQuery->where('on_sale', 1);
        }

        // In Stock filter
        if($this->in_stock) {
            $productQuery->where('in_stock', 1);
        }

        // Price Range filter
        if($this->price_range) {
            // Filter op prijs tussen 0 en de geselecteerde prijs
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }

        // Sorteer de laatste toegevoegde producten
        if($this->sort == 'latest'){
            $productQuery->latest();
        }

        // Sorteer de producten op prijs van laag naar hoog
        if($this->sort == 'lowest_price'){
            $productQuery->orderBy('price', 'asc');
        }

        // Sorteer de producten op prijs van hoog naar laag
        if($this->sort == 'highest_price'){
            $productQuery->orderBy('price', 'desc');
        }

        // Filter de producten op basis van de geselecteerde categorieën, als er categorieën zijn geselecteerd
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
