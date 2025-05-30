{{--PAGINATION CUSTOM--}}
@if ($paginator->hasPages())
    <nav class="custom-pagination mt-5">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span aria-hidden="true" class="page-link">&#8592;</span>
                </li>
            @else
                <li class="page-item">
                    <button type="button" class="page-link"
                            wire:click="previousPage"
                            wire:loading.attr="disabled"
                            aria-label="Previous">&#8592;</button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <button type="button" class="page-link"
                                        wire:click="gotoPage({{ $page }})"
                                        wire:loading.attr="disabled"
                                        aria-label="Go to page {{ $page }}">{{ $page }}</button>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <button type="button" class="page-link"
                            wire:click="nextPage"
                            wire:loading.attr="disabled"
                            aria-label="Next">&#8594;</button>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&#8594;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
