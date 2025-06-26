<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumb extends Component
{
    public $breadcrumbs = [];

    // Bij het laden van het component worden de breadcrumbs opgebouwd
    public function mount()
    {
        $this->breadcrumbs = $this->generateBreadcrumbs();
    }

    // Genereer breadcrumb items op basis van de URL segments
    protected function generateBreadcrumbs()
    {
        $segments = request()->segments();  // STUKKEN VAN DE URL bv. ['shop', 'product', '5']
        $breadcrumbs = [];

        $url = ''; // Lege variabele om de url op te bouwen
        foreach ($segments as $segment) {
            $url .= '/' . $segment; // Bouw de url op bv. /shop, /shop/product, ...
            $breadcrumbs[] = [
                // Zet eerste letter hoofdletter en maak - spatie
                'name' => ucfirst(str_replace('-', ' ', $segment)),
                'url' => $url,
            ];
        }

        return $breadcrumbs;
    }

    public function render()
    {
        return view('livewire.components.breadcrumb');
    }
}
