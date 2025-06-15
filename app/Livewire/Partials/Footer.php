<?php

namespace App\Livewire\Partials;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Component;

class Footer extends Component
{
    public $categories;
    public $brands;

    public function mount(){
        $this->categories = Category::all();
        $this->brands = Brand::all();
    }
    public function render()
    {
        return view('livewire.partials.footer');
    }
}
