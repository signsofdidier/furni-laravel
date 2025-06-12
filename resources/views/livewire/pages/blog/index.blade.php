<div>
    <div class="container py-5">
        <h1 class="mb-4">Blog</h1>

        @foreach ($blogs as $blog)
            <div class="mb-4 border-bottom pb-3">
                <h2 class="h4">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="text-decoration-none text-primary">
                        {{ $blog->title }}
                    </a>
                </h2>
                <small class="text-muted">
                    Gepubliceerd op {{ $blog->published_at->format('d-m-Y') }}
                </small>
                <p class="mt-2">{{ $blog->excerpt }}</p>

                @if ($blog->category)
                    <span class="badge bg-secondary">{{ $blog->category->name }}</span>
                @endif


            </div>
        @endforeach

        <div class="mt-4">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
