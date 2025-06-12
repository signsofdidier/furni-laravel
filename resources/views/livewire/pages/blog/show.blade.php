<div>
    <div class="container py-5">
        <h1 class="mb-3">{{ $blog->title }}</h1>
        <small class="text-muted d-block mb-3">
            Gepubliceerd op {{ $blog->published_at->format('d-m-Y') }}
        </small>

        <hr>

        @if ($blog->category)
            <p class="text-muted">
                Categorie: <strong>{{ $blog->category->name }}</strong>
            </p>
        @endif


        <div class="mt-4">
            {!! \Illuminate\Support\Str::markdown($blog->content) !!}
        </div>
    </div>

    <style>
        .markdown-content h1, h2, h3 {
            margin-top: 1.5rem;
        }

        .markdown-content p {
            margin-bottom: 1rem;
        }

    </style>
</div>
