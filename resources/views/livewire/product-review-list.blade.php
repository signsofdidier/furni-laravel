<div>
    {{-- Aantal reviews --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Customer Reviews</h5>
        <span class="text-muted">{{ $reviews->count() }} review{{ $reviews->count() === 1 ? '' : 's' }}</span>
    </div>

    @forelse ($reviews as $review)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">{{ $review->user->name }}</div>
                    <small class="text-muted">{{ $review->created_at->format('F j, Y') }}</small>
                </div>

                {{-- Rating --}}
                <div class="mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="18" height="18"
                             class="me-1"
                             fill="{{ $i <= $review->rating ? '#FFC107' : '#e4e5e9' }}">
                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.918 5.236L0.832 5.773L4.63 9.197L3.57 14.199L8 11.645L12.43 14.199L11.37 9.197L15.168 5.773Z"/>
                        </svg>
                    @endfor
                </div>

                {{-- Titel en body --}}
                @if ($review->title)
                    <h6 class="fw-semibold">{{ $review->title }}</h6>
                @endif
                <p class="mb-0">{{ $review->body }}</p>

                {{-- Edit knop --}}
                @auth
                    @if ($review->user_id === auth()->id())
                        <div class="mt-3">
                            <button type="button" onclick="Livewire.emit('editReview')" class="btn btn-sm btn-outline-primary">

                            ✏️ Edit your review
                            </button>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="text-muted">No reviews yet.</div>
    @endforelse
</div>
