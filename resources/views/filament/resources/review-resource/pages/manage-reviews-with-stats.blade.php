<x-filament::page>
    <div class="flex gap-4 mb-6">
        <x-filament::button
            wire:click="switchTab('reviews')"
            color="{{ $activeTab === 'reviews' ? 'primary' : 'gray' }}"
        >
            📋 Reviews
            @if ($pendingReviewCount > 0)
                <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-500 text-white">
                    {{ $pendingReviewCount }}
                </span>
            @endif
        </x-filament::button>

        <x-filament::button
            wire:click="switchTab('ratings')"
            color="{{ $activeTab === 'ratings' ? 'primary' : 'gray' }}"
        >
            ⭐ Product Ratings
        </x-filament::button>


    </div>

    {{ $this->table }}
</x-filament::page>
