<?php

namespace App\Livewire\Pages\Blog;

use Livewire\Component;
use App\Models\Blog;

class Show extends Component
{
    public $blog;

    public function mount($slug)
    {
        $this->blog = Blog::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.pages.blog.show');
    }
}
