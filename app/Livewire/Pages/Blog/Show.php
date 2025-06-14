<?php

namespace App\Livewire\Pages\Blog;

use Livewire\Component;
use App\Models\Blog;

class Show extends Component
{
    public $blog;
    public $prevBlog;
    public $nextBlog;
    public $latestBlog;

    public function mount($slug)
    {
        // haal de blog op volgens de slug
        $this->blog = Blog::with(['categories', 'user'])->where('slug', $slug)->firstOrFail();

        // Zoek vorige en vorige blog op basis van created_at
        $this->prevBlog = Blog::where('created_at', '<', $this->blog->created_at)
            ->whereNull('deleted_at') // Zoek alleen actieve blogs (sluit softdeleted uit)
            ->orderBy('created_at', 'desc') // sorteer op created_at om de vorige blog te vinden
            ->first(); // eerstvolgende blog

        // Zoek vorige en volgende blog op basis van created_at
        $this->nextBlog = Blog::where('created_at', '>', $this->blog->created_at)
            ->whereNull('deleted_at') // Zoek alleen actieve blogs (sluit softdeleted uit)
            ->orderBy('created_at', 'asc') // sorteer op created_at om de vorige blog te vinden
            ->first(); // eerstvolgende blog

        $this->latestBlog = Blog::where('id', '!=', $this->blog->id)
            ->whereNull('deleted_at') // Zoek alleen actieve blogs (sluit softdeleted uit)
            ->latest('created_at')
            ->take(5) // haal de laatste 4 blogs
            ->get(); // haal alle blogs

    }

    public function render()
    {
        return view('livewire.pages.blog.show', [
            'prevBlog' => $this->prevBlog,
            'nextBlog' => $this->nextBlog,
            'latestBlog' => $this->latestBlog,
        ]);
    }
}
