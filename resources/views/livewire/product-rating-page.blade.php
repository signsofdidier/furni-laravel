<div>
    <div class="d-flex align-items-center">
        <div class="d-flex star-rating">
            @for ($i = 1; $i <= 5; $i++)
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="17"
                    viewBox="0 0 16 15"
                    fill="{{ $i <= round($average) ? '#FFAE00' : '#B2B2B2' }}"
                    style="margin-right: 4px;"
                    title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"
                >
                    <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" />
                </svg>
            @endfor
        </div>

        {{-- Gemiddelde score --}}
        <span class="ms-2 text-muted">{{ number_format($average) }}/5</span>

        {{-- Totaal aantal ratings --}}
        <span class="ms-2">({{ $total }})</span>
    </div>
</div>
