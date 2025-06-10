<x-filament::page>
    <div class="flex gap-4 mb-6">
        <x-filament::button
            wire:click="switchTab('ratings')"
            color="{{ $activeTab === 'ratings' ? 'primary' : 'gray' }}"
        >
            ⭐ Product Ratings
        </x-filament::button>

        <x-filament::button
            wire:click="switchTab('reviews')"
            color="{{ $activeTab === 'reviews' ? 'primary' : 'gray' }}"
        >
            📋 Reviews
        </x-filament::button>
    </div>

    {{ $this->table }}
</x-filament::page>
