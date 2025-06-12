<?php

namespace App\Livewire\Pages\Blog;

use Livewire\Component;
use App\Models\Blog;

class Show extends Component
{
    public $blog;

    public function mount($slug)
    {
        // haal de blog op volgens de slug
        $this->blog = Blog::with(['categories', 'user'])->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.pages.blog.show');
    }
}
