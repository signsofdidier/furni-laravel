<div>
    <div class="collection mt-100">
        <div class="container">
            <div class="row flex-row-reverse">
                <!-- product area start -->
                <div class="col-lg-9 col-md-12 col-12">
                    <div class="filter-sort-wrapper d-flex justify-content-between flex-wrap">
                        <div class="collection-title-wrap d-flex align-items-end">
                            <h2 class="collection-title heading_24 mb-0">All products</h2>
                            <p class="collection-counter text_16 mb-0 ms-2">(237 items)</p>
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
                            @foreach($products as $product)
                                <div class="col-lg-4 col-md-6 col-6" wire:key="{{ $product->id }}">
                                    <div class="product-card">
                                        <div class="product-card-img">
                                            <a class="product-hover-zoom" href="{{ url('/products') }}/{{ $product->slug }}">
                                                <img class="primary-img" src="{{ url('storage', $product->images[0]) }}"
                                                     alt="product-img">
                                            </a>

                                            {{--<div class="product-badge">
                                                <span class="badge-label badge-percentage rounded">-44%</span>
                                            </div>--}}

                                            <div
                                                class="product-card-action product-card-action-2 justify-content-center">

                                                {{-- WISHLIST BUTTON --}}
                                                <a href="#" class="action-card action-wishlist">
                                                    <svg class="icon icon-wishlist" width="26" height="22"
                                                         viewBox="0 0 26 22" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"
                                                            fill="#00234D" />
                                                    </svg>
                                                </a>

                                                {{--Add to cart button--}}
                                                <button wire:click.prevent='addToCart({{ $product->id }})' type="button" class="action-card action-addtocart">
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

                                        {{-- COLORS --}}
                                        <div class="product-card-details">
                                            <ul class="color-lists list-unstyled d-flex align-items-center">
                                                {{--@foreach($colors as $color)
                                                    <li>
                                                        <a href="#"
                                                           class="color-swatch swatch-black active"></a>
                                                    </li>
                                                @endforeach--}}

                                            </ul>

                                            {{-- PRODUCT TITLE --}}
                                            <h3 class="product-card-title">
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

                        </div>


                        {{--<div class="row">
                            <div class="col-lg-9 offset-lg-3">
                                <nav wire:navigate class="custom-pagination">
                                    {{ $products->links('vendor.pagination.custom') }}
                                </nav>
                            </div>
                        </div>--}}
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
                        <div class="filter-widget d-lg-none">
                            <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom"
                                 data-bs-toggle="collapse" data-bs-target="#filter-mobile-sort">
                                        <span>
                                            <span class="sorting-title me-2">Sort by:</span>
                                            <span class="active-sorting">Featured</span>
                                        </span>
                                <span class="faq-heading-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </span>
                            </div>
                            <div id="filter-mobile-sort" class="accordion-collapse collapse show">
                                <ul class="sorting-lists-mobile list-unstyled m-0">
                                    <li><a href="#" class="text_14">Featured</a></li>
                                    <li><a href="#" class="text_14">Best Selling</a></li>
                                    <li><a href="#" class="text_14">Alphabetically, A-Z</a></li>
                                    <li><a href="#" class="text_14">Alphabetically, Z-A</a></li>
                                    <li><a href="#" class="text_14">Price, low to high</a></li>
                                    <li><a href="#" class="text_14">Price, high to low</a></li>
                                    <li><a href="#" class="text_14">Date, old to new</a></li>
                                    <li><a href="#" class="text_14">Date, new to old</a></li>
                                </ul>
                            </div>
                        </div>

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

                            {{--<div class="filter-price d-flex align-items-center justify-content-between">
                                <div class="filter-field">
                                    <input class="field-input" type="number" placeholder="€0" min="0"
                                           max="5000">
                                </div>
                                <div class="filter-separator px-3">To</div>
                                <div class="filter-field">
                                    <input class="field-input" type="number" min="0" placeholder="€5.000"
                                           max="5000">
                                </div>
                            </div>--}}
                        </div>

                        {{-- Colors filter --}}
                        <div x-data="{ open: true }" class="filter-widget filter-color">
                            <div @click="open = !open" class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom">
                                Colors
                            </div>
                            <div x-show="open" x-collapse id="filter-color">
                                <ul class="color-lists list-unstyled d-flex align-items-center flex-wrap">
                                    @foreach($colors as $color)
                                        <li class="me-1 mb-2" wire:key="color-{{ $color->id }}">
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
                                                        width: 28px;
                                                        height: 28px;
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
                                            <input type="checkbox" wire:model="selectedBrands" value="{{ $brand->id }}" />
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

</div>

{{-- COLOR FILTER STYLING --}}
<style>
    /* Zorg dat bij selectie een duidelijke outline/ring verschijnt */
    .color-lists input[type="checkbox"]:checked + .color-swatch {
        border: 3px solid #F76B6A;
        box-shadow: 0 0 0 2px #F76B6A;
    }
    .color-swatch {
        transition: border 0.2s, box-shadow 0.2s;
    }
</style>






