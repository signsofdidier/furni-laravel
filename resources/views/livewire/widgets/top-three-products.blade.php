<div>
    <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body d-flex justify-content-between align-items-start">
            <div style="width: 100%;">
                <div class="text-uppercase text-muted fw-semibold mb-2" style="font-size: 0.75rem;">
                    Top Purchased Products
                </div>
                <ul class="list-group list-group-flush">
                    @foreach($topProducts as $name => $qty)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>{{ $name }}</span>
                            <span class="badge bg-dark rounded-pill">{{ $qty }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
