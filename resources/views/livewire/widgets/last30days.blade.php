<div>
    <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <div class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.75rem;">
                    Purchased in Last 30 Days
                </div>
                <div class="text-success fw-bold" style="font-size: 2rem;">
                    {{ $total }}
                </div>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="background-color: rgba(25,135,84,0.1); width: 50px; height: 50px;">
                <x-heroicon-o-calendar class="w-6 h-6 text-success" />
            </div>
        </div>
    </div>
</div>
