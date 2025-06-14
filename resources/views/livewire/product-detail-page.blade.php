<div>
    <div class="product-page mt-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-gallery product-gallery-vertical d-flex">
                        <div class="product-img-large">
                            <div wire:ignore class="img-large-slider common-slider" data-slick='{
                                        "slidesToShow": 1,
                                        "slidesToScroll": 1,
                                        "dots": false,
                                        "arrows": false,
                                        "asNavFor": ".img-thumb-slider"
                                    }'>
                                @foreach($product->images as $image)
                                    <div class="img-large-wrapper">
                                        <a href="{{ url('storage', $image) }}" data-fancybox="gallery">
                                            <img class="w-100 h-100" src="{{ url('storage', $image) }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="product-img-thumb">
                            <div  class="img-thumb-slider common-slider" data-vertical-slider="true" data-slick='{
                                        "slidesToShow": 5,
                                        "slidesToScroll": 1,
                                        "dots": false,
                                        "arrows": true,
                                        "infinite": false,
                                        "speed": 300,
                                        "cssEase": "ease",
                                        "focusOnSelect": true,
                                        "swipeToSlide": true,
                                        "asNavFor": ".img-large-slider"
                                    }'>
                                @foreach($product->images as $image)
                                    <div>
                                        <div class="img-thumb-wrapper">
                                            <img src="{{ url('storage', $image) }}" alt="img">
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="activate-arrows show-arrows-always arrows-white d-none d-lg-flex justify-content-between mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-details ps-lg-4">

                        {{-- PRODUCT AVAILABILITY --}}
                        <div class="mb-3">
                            {{-- Als product in stock is dan in stock anders sold out --}}
                            <span class="product-availability">
                            @if($product->in_stock == 1)
                                    In Stock
                                @else
                                    Sold Out
                                @endif
                        </span>
                        </div>

                        {{-- PRODUCT NAME --}}
                        <h2 class="product-title mb-3">{{$product->name }}</h2>

                        {{-- STAR RATINGS --}}
                        <livewire:product-rating-page :product="$product"/>

                        <div class="product-price-wrapper mt-3 mb-4">
                            <span class="product-price regular-price">{{ Number::currency($product->price, 'EUR') }}</span>
                            {{--<del class="product-price compare-price ms-2">$32.00</del>--}}
                        </div>

                        <div class="[&>ul]:list-disc [&>ul]">
                            <p class="max-w-md text-gray-700 dark:text-gray-400">
                                {{--install composer require league/commonmark om markdown te doen werken--}}
                                {!!
                                    str($product->description)
                                        ->markdown([
                                            // Strip alle raw HTML-tags uit de bron
                                            'html_input' => 'strip',
                                            // Blokkeer links die beginnen met “javascript:” of andere onveilige protocollen
                                            'allow_unsafe_links' => false,
                                        ])
                                        // Verwijder alles wat niet in de standaard allowlist valt (XSS-preventie)
                                        ->sanitizeHtml()
                                !!}
                            </p>
                        </div>

                        {{-- BRANDS --}}
                        <div class="product-vendor product-meta mb-3">
                            <strong class="label">Brand:</strong> {{ $product->brand->name }}
                        </div>

                        @if(!$product->in_stock)
                            {{-- alles is uitverkocht --}}
                            <p class="text-danger">This product is out of stock</p>
                        @endif

                        {{-- COLORS --}}
                        <div class="product-variant-wrapper">
                            <div class="product-variant product-variant-color">
                                <strong class="label mb-1 d-block">Color:</strong>

                                <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                                    @foreach($product->colors as $color)
                                        @php
                                            $stock = $product->stockForColorId($color->id);
                                            $isOutOfStock = $stock === 0;
                                        @endphp
                                        <li class="variant-item">
                                            <input
                                                type="radio"
                                                id="color-{{ $color->id }}"
                                                name="selectedColorId"
                                                value="{{ $color->id }}"
                                                wire:model="selectedColorId"
                                                class="visually-hidden"
                                                {{ $isOutOfStock ? 'disabled' : '' }}
                                            >
                                            <label
                                                for="color-{{ $color->id }}"
                                                class="variant-label rounded-circle d-inline-block position-relative"
                                                style="
                                                    width: 1.5rem;
                                                    height: 1.5rem;
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
                                                title="{{ $color->name }}{{ $isOutOfStock ? ' (Out of stock)' : '' }}"
                                            >
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                @error('selectedColorId')
                                <div class="text-danger small">
                                    {{ $message }}
                                </div>
                                @enderror

                            </div>
                        </div>


                        @error('quantity')
                            <div
                                x-data="{ show: true }"
                                x-init="setTimeout(() => show = false, 3000)"
                                x-show="show"
                                class="text-danger small mt-1"
                            >
                            {{ $message }}
                        </div>
                        @enderror
                        <div class="misc d-flex align-items-end justify-content-between mt-4">
                            <div class="quantity d-flex align-items-center justify-content-between">

                                {{-- DECREASE --}}
                                <button wire:click="decreaseQuantity" class="qty-btn dec-qty" {{ $quantity <= 1 ? 'disabled' : '' }}><img src="{{ asset('assets/img/icon/minus.svg') }}" alt="minus"></button>

                                {{-- QUANTITY --}}
                                <span class="qty-input">{{ max($quantity, 1) }}</span>

                                {{-- INCREMENT --}}
                                @php
                                    $maxStock = $this->maxStock;
                                @endphp

                                <button
                                    wire:click="increaseQuantity"
                                    class="qty-btn inc-qty"
                                    title="{{ $maxStock !== null && $quantity >= $maxStock ? 'Max stock reached for selected color' : '' }}"
                                    {{ $maxStock !== null && $quantity >= $maxStock ? 'disabled' : '' }}
                                >
                                    <img src="{{ asset('assets/img/icon/plus.svg') }}" alt="plus">
                                </button>


                            </div>
                        </div>

                        <form class="product-form" action="#">
                            <div class="product-form-buttons d-flex align-items-center justify-content-between mt-4">

                                {{-- ADD TO CART --}}
                                <button wire:click="addToCart({{ $product->id }})" {{ !$product->in_stock ? 'disabled' : '' }} type="button" class="position-relative btn-atc btn-add-to-cart loader">
                                    ADD TO CART
                                </button>

                                {{-- WISHLIST --}}
                                <a href="wishlist.html" class="product-wishlist">
                                    <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" fill="#00234D"></path>
                                    </svg>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- product tab start -->
    <div class="product-tab-section mt-100" data-aos="fade-up" data-aos-duration="700">
        <div class="container">
            <div class="tab-list product-tab-list">
                <nav class="nav product-tab-nav">
                    <a class="product-tab-link tab-link active" href="#preview" data-bs-toggle="tab">Reviews</a>
                    <a class="product-tab-link tab-link" href="#pshipping" data-bs-toggle="tab">Shipping & Returns</a>
                    <a class="product-tab-link tab-link" href="#pstyle" data-bs-toggle="tab">Style with</a>

                </nav>
            </div>
            <div class="tab-content product-tab-content">
                <div id="pshipping" class="tab-pane fade">
                    <div class="desc-content">
                        <h4 class="heading_18 mb-3">Returns within the European Union</h4>
                        <p class="text_16 mb-4">The European law states that when an order is being returned, it is mandatory for the company to refund the product price and shipping costs charged for the original shipment. Meaning: one shipping fee is paid by us.</p>
                        <p class="text_16 mb-4">Standard Shipping: If you placed an order using "standard shipping" and you want to return it, you will be refunded the product price and initial shipping costs. However, the return shipping costs will be paid by you.</p>
                        <p class="text_16">Free Shipping: If you placed an order using "free shipping" and you want to return it, you will be refunded the product price, but since we paid for the initial shipping, you will pay for the return.</p>
                    </div>
                </div>
                <div id="pstyle" class="tab-pane fade">
                    <div class="desc-content">
                        <h4 class="heading_18 mb-3">Style with</h4>
                        <p class="text_16 mb-4">Please also bear in mind that shipping goods back and forth generates greenhouse gases that are accelerating climate change. We encourage you to choose your items carefully to avoid unnecessary return shipments.</p>
                        <p class="text_16 mb-4">You have to pay for return shipping if you want to exchange your product for another size or the package is returned because it has not been picked up at the post office.</p>
                    </div>
                </div>

                {{-- REVIEWS --}}
                <div id="preview" class="tab-pane fade show active">
                    <div class="review-area accordion-parent">
                        <livewire:product-review-form :product="$product" />
                        <livewire:product-review-list :product="$product" />


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product tab end -->

    <!-- you may also like start -->
    @if($featuredProducts->count() > 1)
        <div class="featured-collection-section mt-100 home-section overflow-hidden">
        <div class="container">
            <div class="section-header">
                <h2 class="section-heading">You may also like</h2>
            </div>

            <div class="product-container position-relative">
                <div wire:ignore class="common-slider" data-slick='{
                        "slidesToShow": 4,
                        "slidesToScroll": 1,
                        "dots": false,
                        "arrows": true,
                        "responsive": [
                        {
                            "breakpoint": 1281,
                            "settings": {
                            "slidesToShow": 3
                            }
                        },
                        {
                            "breakpoint": 768,
                            "settings": {
                            "slidesToShow": 2
                            }
                        }
                        ]
                    }'>

                    @foreach($featuredProducts as $featured)
                        <div class="new-item" data-aos="fade-up" data-aos-duration="300">
                                <div class="product-card">
                                    <div class="product-card-img text-center">
                                        <a class="product-hover-zoom" href="{{ url('/products') }}/{{ $featured->slug }}">
                                            <img class="primary-img" src="{{ url('storage', $featured->images[0]) }}"
                                                 alt="product-img">
                                        </a>

                                        <div class="product-card-action product-card-action-2">
                                            <a href="{{ url('/products') }}/{{ $featured->slug }}" class="quickview-btn w-100 btn-primary">VIEW</a>
                                            {{--<button wire:click.prevent="addToCart({{ $featured->id }})" type="button" class="addtocart-btn btn-primary">
                                                ADD TO CART
                                            </button>--}}
                                        </div>

                                        <a href="wishlist.html" class="wishlist-btn card-wishlist">
                                            <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22"
                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"
                                                    fill="black" />
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="product-card-details text-center mt-2">

                                        {{-- STAR RATINGS --}}
                                        <div class="d-flex justify-content-center">
                                            <livewire:product-rating-page :product="$featured" />
                                        </div>

                                        {{-- PRODUCT TITLE--}}
                                        <h3 class="product-card-title mt-1"><a href="{{ url('/products') }}/{{ $featured->slug }}">{{ $featured->name }}</a>
                                        </h3>

                                        {{-- PRODUCT PRICE--}}
                                        <div class="product-card-price">
                                            <span class="card-price-regular">{{ Number::currency($featured->price, 'EUR') }}</span>
                                            {{--<span class="card-price-compare text-decoration-line-through">$1759</span>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>
                <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
            </div>
        </div>
    </div>
    @endif
    <!-- you may also lik end -->

    <style>
        /* DISABLED BUTTON */
        button[disabled] {
            cursor: not-allowed !important;
            opacity: 0.5;
            pointer-events: all; /* nodig voor cursor effect */
        }
    </style>
</div>
