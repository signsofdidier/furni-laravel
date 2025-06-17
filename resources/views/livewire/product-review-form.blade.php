<div>

    @if($canReview)
        {{-- Alleen toon "Write a review" als user nog geen review heeft --}}
        @auth
            @if (!$this->hasUserReviewed() && !$showForm)
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-outline-secondary" wire:click="showReviewForm">
                        ✍️ Write a review
                    </button>
                </div>
            @endif
        @endauth

        @auth
            @php
                $pendingReview = $product->reviews()
                    ->where('user_id', auth()->id())
                    ->where('approved', false)
                    ->first();
            @endphp

            @if ($pendingReview)
                <div class="alert alert-warning mt-3">
                    Your review is pending approval and will be shown once approved.
                </div>
            @endif
        @endauth

        @if ($showForm)
            <div class="card shadow-sm p-4 mb-4">


                <h5 class="mb-3 text-start">Write a review</h5>

                {{-- Rating --}}
                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <div class="d-flex">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg wire:click="$set('rating', {{ $i }})"
                                 xmlns="http://www.w3.org/2000/svg"
                                 width="20" height="20"
                                 class="me-1"
                                 style="cursor:pointer"
                                 fill="{{ $i <= $rating ? '#FFC107' : '#e4e5e9' }}">
                                <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z"/>
                            </svg>
                        @endfor
                    </div>
                    @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Title --}}
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" wire:model.defer="title" placeholder="Short summary...">
                    @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Body --}}
                <div class="mb-3">
                    <label class="form-label">Your Review</label>
                    <textarea class="form-control" wire:model.defer="body" rows="4"
                              placeholder="Share your experience..."></textarea>
                    @error('body') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button wire:click="save" class="btn btn-primary">
                        Submit
                    </button>
                    <button wire:click="$set('showForm', false)" type="button" class="btn btn-outline-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        @endif
@endif
</div>
