<div>
    <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <div class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.75rem;">
                    Total Products Purchased
                </div>
                <div class="text-primary fw-bold" style="font-size: 2rem;">
                    {{ $total }}
                </div>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(13,110,253,0.1); width: 50px; height: 50px;">
                {{-- Heroicon: Shopping Bag --}}
                <x-heroicon-o-shopping-bag class="w-6 h-6 text-primary" />
            </div>
        </div>
    </div>
</div>
