<div>
    <div class="collection mt-100">
        <div class="container">
            <div class="row flex-row-reverse">
                <!-- product area start -->
                <div class="col-lg-9 col-md-12 col-12">
                    <div class="filter-sort-wrapper d-flex justify-content-between flex-wrap">
                        <div class="collection-title-wrap d-flex align-items-end">
                            <h2 class="collection-title heading_24 mb-0">All products</h2>
                            <p class="collection-counter text_16 mb-0 ms-2">({{ $filtered_count }} items)</p>
                        </div>
                        <div class="filter-sorting">
                            <div class="collection-sorting position-relative d-none d-lg-block">
                                <div class="dropdown">
                                    <select wire:model.live="sort"
                                            class="form-select w-auto  border border-0"
                                            id="sortSelect"
                                            aria-label="Sort products">
                                        <option value="latest">Sort by latest</option>
                                        <option value="lowest_price">Sort by Lowest Price</option>
                                        <option value="highest_price">Sort by Highest Price</option>
                                    </select>
                                </div>
                            </div>
                            <div class="filter-drawer-trigger mobile-filter d-flex align-items-center d-lg-none">
                                        <span class="mobile-filter-icon me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round" class="icon icon-filter">
                                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                            </svg>
                                        </span>
                                <span class="mobile-filter-heading">Filter and Sorting</span>
                            </div>
                        </div>
                    </div>


                    <!-- Products -->
                    <div class="collection-product-container">
                        <div class="row">
                           @if($products->count() > 0)
                                @foreach($products as $product)
                                    <div class="col-lg-4 col-md-6 col-12" wire:key="{{ $product->id }}">
                                        <div class="product-card">
                                            <div class="product-card-img">
                                                <a class="product-hover-zoom" href="{{ url('/products') }}/{{ $product->slug }}">
                                                    <img class="primary-img" src="{{ isset($product->images[0]) ? url('storage/' . $product->images[0]) : asset('img/product-placeholder.jpg') }}"
                                                         alt="{{  $product->name }}">
                                                </a>

                                                @php
                                                    $stock = $stockPerProduct[$product->id] ?? null;
                                                @endphp

                                                {{-- PRODUCT BADGE, ON SALE, AVAILABLE AGAIN SOON, LAST ITEMS --}}
                                                <div class="product-badge d-flex gap-1 position-absolute top-0 start-0 p-2">
                                                    @php
                                                        $stockCount = $product->productColorStocks->sum('stock');
                                                    @endphp

                                                    {{--ON SALE--}}
                                                    <div>
                                                        @if($product->on_sale == 1 && $stockCount > 0)
                                                            <span class="badge-label badge-percentage rounded">On Sale</span>
                                                        @endif
                                                    </div>

                                                    {{-- AVAILABLE AGAIN SOON EN LAST ITEMS --}}
                                                    <div>
                                                        @if($stockCount == 0)
                                                            <span class="badge-label bg-secondary text-white rounded">Available again soon</span>
                                                        @elseif($stockCount < 11)
                                                            <span class="badge-label bg-warning text-white rounded">Last Items</span>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div
                                                    class="product-card-action product-card-action-2 justify-content-center">

                                                    {{-- WISHLIST BUTTON --}}
                                                    <div class="action-card action-wishlist">
                                                        <livewire:wishlist-button :product="$product" :wire:key="'wishlist-'.$product->id.'-'.Str::uuid()" />
                                                    </div>

                                                    {{--ADD TO CART BUTTON--}}
                                                    {{-- Als de stock leeg is of 0, dan is de button disabled --}}
                                                    <button
                                                        type="button"
                                                        class="action-card action-addtocart"
                                                        @if(!$product->in_stock) disabled @endif
                                                        wire:click.prevent="addToCart({{ $product->id }})"
                                                    >

                                                        <svg class="icon icon-cart" width="24" height="26"
                                                             viewBox="0 0 24 26" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z"
                                                                fill="#00234D" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="product-card-details">
                                                {{-- COLOR SELECT --}}
                                                <ul class="color-lists list-unstyled d-flex align-items-center mt-2 mb-2">
                                                    {{-- LEGE OPTIE (onzichtbaar) --}}
                                                    <input type="radio" wire:model.live="selectedColorPerProduct.{{ $product->id }}" value="" class="d-none">

                                                    @foreach($product->colors as $color)
                                                        @php
                                                            $stock = $product->stockForColorId($color->id);
                                                            $isOutOfStock = $stock === 0;
                                                        @endphp

                                                        <li class="variant-item">
                                                            <input
                                                                type="radio"
                                                                id="color-{{ $product->id }}-{{ $color->id }}"
                                                                name="selectedColorPerProduct[{{ $product->id }}]"
                                                                value="{{ $color->id }}"
                                                                wire:model="selectedColorPerProduct.{{ $product->id }}"
                                                                class="visually-hidden"
                                                                {{ $isOutOfStock ? 'disabled' : '' }}
                                                            >
                                                            <label
                                                                for="color-{{ $product->id }}-{{ $color->id }}"
                                                                class="variant-label rounded-circle d-inline-block position-relative"
                                                                style="
                                                                width: 1rem;
                                                                height: 1rem;
                                                                background-color: {{ $color->hex }};
                                                                border: 1px solid #ccc;
                                                                box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
                                                                cursor: {{ $isOutOfStock ? 'not-allowed' : 'pointer' }};
                                                                opacity: {{ $isOutOfStock ? '0.4' : '1' }};
                                                                @if($isOutOfStock)
                                                                    background-image: linear-gradient(
                                                                        to top left,
                                                                        transparent 48%,
                                                                        #999 48.5%,
                                                                        #999 51.5%,
                                                                        transparent 52%
                                                                    );
                                                                @endif
                                                            "
                                                                title="{{ $color->name }}{{ $isOutOfStock ? ' (Available again soon)' : '' }}"
                                                            >
                                                            </label>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                                @error("selectedColorPerProduct.$product->id")
                                                <div
                                                    x-data="{ show: true }"
                                                    x-init="setTimeout(() => show = false, 3000)"
                                                    x-show="show"
                                                    class="text-danger small my-1"
                                                >
                                                    {{ $message }}
                                                </div>
                                                @enderror




                                                {{-- STAR RATINGS --}}
                                                <livewire:product-rating-page :product="$product" :wire:key="'rating-'.$product->id" />

                                                {{-- PRODUCT TITLE --}}
                                                <h3 class="product-card-title mt-1">
                                                    <a href="{{ url('/products') }}/{{ $product->slug }}">{{ $product->name }}</a>
                                                </h3>

                                                {{-- PRODUCT PRICE --}}
                                                <div class="product-card-price">
                                                    <span class="card-price-regular">{{ Number::currency($product->price ?? 0, 'EUR') }}</span>
                                                    {{--<span class="card-price-compare text-decoration-line-through">$1759</span>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning mt-4">
                                    No products found.
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="mt-5">
                        {{ $products->links() }}
                    </div>

                </div>
                <!-- product area end -->

                <!-- sidebar start -->
                {{-- START FILTERS --}}
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="collection-filter filter-drawer">

                        {{-- MOBILE filter --}}
                        <div class="filter-widget d-lg-none d-flex align-items-center justify-content-between">
                            <h5 class="heading_24">Filter By</h5>
                            <button type="button" class="btn-close text-reset filter-drawer-trigger d-lg-none"></button>
                        </div>

                        {{-- MOBILE SORT BY filter --}}

                        {{-- Categories filter --}}
                        <div class="filter-widget">
                            <div x-data="{ open: true }" class="filter-widget">
                                <div @click="open = !open"
                                     class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom"
                                     role="button">
                                    Categories
                                </div>

                                <div x-show="open" x-collapse class="filter-body">
                                    <ul class="filter-lists list-unstyled mb-0">
                                        @foreach($categories as $category)
                                            <li class="filter-item" wire:key="{{ $category->id }}">
                                                <label class="filter-label">
                                                    <input type="checkbox"
                                                           wire:model.live="selected_categories"
                                                           value="{{ $category->id }}"
                                                           class="w-4 h-4 mr-2">
                                                    <span class="filter-checkbox rounded me-2"></span>
                                                    <span class="filter-text">{{ $category->name }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Availability filter --}}
                        <div x-data="{ open: true }" class="filter-widget">
                            <div @click="open = !open" class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom" >
                                Availability
                            </div>
                            <div x-show="open" x-collapse id="filter-availability">
                                <ul class="filter-lists list-unstyled mb-0">
                                    <li class="filter-item">
                                        <label for="in_stock" class="filter-label">
                                            <input type="checkbox" id="in_stock" wire:model.live="in_stock" value="1"/>
                                            <span class="filter-checkbox rounded me-2"></span>
                                            <span class="filter-text">In Stock</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Price filter --}}
                        <div x-data="{ open:true }" class="filter-widget">
                            <div @click="open = !open" class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom">
                                Price
                            </div>
                            <div x-show="open" x-collapse class="mt-4">
                                <div class="fw-semibold mb-2">
                                    {{ Number::currency($price_range, 'EUR') }}
                                </div>

                                <input
                                    type="range"
                                    wire:model.live="price_range"
                                    class="form-range"
                                    min="0"
                                    max="5000"
                                    step="1"
                                >

                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-primary fw-bold">€0</span>
                                    <span class="text-primary fw-bold">€5.000</span>
                                </div>
                            </div>
                        </div>

                        {{-- Colors filter --}}
                        <div x-data="{ open: true }" class="filter-widget filter-color">
                            <div @click="open = !open" class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom">
                                Colors
                            </div>
                            <div x-show="open" x-collapse id="filter-color">
                                <ul class="color-lists list-unstyled d-flex align-items-center flex-wrap">
                                    @foreach($colors as $color)
                                        <li class="mb-2" wire:key="color-{{ $color->id }}">
                                            <label style="cursor:pointer;">
                                                <input
                                                    type="checkbox"
                                                    wire:model.live="selected_colors"
                                                    value="{{ $color->id }}"
                                                    class="d-none"
                                                >
                                                <span
                                                    class="color-swatch"
                                                    style="
                                                        display: inline-block;
                                                        width: 22px;
                                                        height: 22px;
                                                        border-radius: 50%;
                                                        background: {{ $color->hex }};
                                                        border: 2px solid #bbb;
                                                        vertical-align: middle;
                                                    "
                                                    title="{{ $color->name }}"
                                                ></span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>

                        {{-- Brands filter --}}
                        <div x-data="{ open: true }" class="filter-widget">
                            <div @click="open = !open" class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom"
                                 data-bs-toggle="collapse" data-bs-target="#filter-vendor">
                                Brands
                            </div>
                            <ul x-show="open" x-collapse class="filter-lists list-unstyled mb-0">
                                @foreach($brands as $brand)
                                    <li class="filter-item" wire:key="{{ $brand->id }}">
                                        <label class="filter-label">
                                            <input type="checkbox" wire:model.live="selected_brands" value="{{ $brand->id }}" />
                                            <span class="filter-checkbox rounded me-2"></span>
                                            <span class="filter-text">{{ $brand->name }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Product Tags filter --}}
                        <div x-data="{ open: true }" class="filter-widget">
                            <div @click="open = !open" class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom"
                                 data-bs-toggle="collapse" data-bs-target="#filter-vendor">
                                Product Tags
                            </div>
                            <ul x-show="open" x-collapse class="filter-lists list-unstyled mb-0">
                                <li class="filter-item">
                                    <label class="filter-label">
                                        <input type="checkbox" wire:model.live="featured" value="1" />
                                        <input type="checkbox" wire:model.live="featured" value="1" />
                                        <span class="filter-checkbox rounded me-2"></span>
                                        <span class="filter-text">Featured Products</span>
                                    </label>
                                </li>
                                <li class="filter-item">
                                    <label class="filter-label">
                                        <input type="checkbox" wire:model.live="on_sale" value="1" />
                                        <span class="filter-checkbox rounded me-2"></span>
                                        <span class="filter-text">On Sale</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        {{-- END FILTERS --}}
                    </div>
                </div>
                <!-- sidebar end -->
            </div>

        </div>
    </div>

    {{-- COLOR FILTER STYLING --}}
    <style>
        /* Visuele highlight voor geselecteerde kleur (radio button) */
        input[type="radio"]:checked + label.variant-label {
            border: 1px solid #F76B6A !important;
            box-shadow: 0 0 0 2px #F76B6A !important;
        }

        /* Soepel effect bij hover of selectie */
        label.variant-label {
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        /* Standaard style voor disabled buttons in je shop */
        button[disabled] {
            cursor: not-allowed !important;
            opacity: 0.5;
            pointer-events: all;
        }

        /* Geselecteerde kleurfilter krijgt accentkleur (roze) */
        input[type="checkbox"]:checked + .color-swatch {
            border: 2px solid #F76B6A !important;
            box-shadow: 0 0 0 2px rgba(247, 107, 106, 0.4);
        }
    </style>
</div>
