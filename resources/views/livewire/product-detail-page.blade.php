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
                                            <img src="{{ url('storage', $image) }}" alt="{{ $product->name }}">
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
                            {{-- Als product in stock is dan in stock anders Available again soon --}}
                            <span class="product-availability">
                            @if($product->in_stock == 1)
                                    In Stock
                                @else
                                    Available again soon
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
                            <p class="text-danger">This product will be available again soon</p>
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
                                                wire:click="$set('selectedColorId', {{ $color->id }})"
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
                                                title="{{ $color->name }}{{ $isOutOfStock ? ' (Available again soon)' : '' }}"
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
                                <button
                                    wire:click="decreaseQuantity"
                                    class="qty-btn dec-qty"
                                    {{ !$canDecrease ? 'disabled' : '' }}
                                >
                                    <img src="{{ asset('assets/img/icon/minus.svg') }}" alt="minus">
                                </button>

                                {{-- QUANTITY --}}
                                <span class="qty-input">{{ max($quantity, 1) }}</span>


                                {{-- INCREMENT --}}
                                @php
                                    $maxStock = $this->maxStock;
                                @endphp

                                <button
                                    wire:click="increaseQuantity"
                                    class="qty-btn inc-qty"
                                    title="{{ !$canIncrease ? 'Max stock reached or no color selected' : '' }}"
                                    {{ !$canIncrease ? 'disabled' : '' }}
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

                                {{-- WISHLIST BIG --}}
                                <div class="product-wishlist">
                                    <livewire:wishlist-button :product="$product" :wire:key="'wishlist-page-'.$product->id" />
                                </div>
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
            <div class="tab-content product-tab-content">
                {{-- REVIEWS --}}
                <div>
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
                                                 alt="{{ $featured->name }}">
                                        </a>

                                        <div class="product-card-action product-card-action-2">
                                            <a href="{{ url('/products') }}/{{ $featured->slug }}" class="quickview-btn w-100 btn-primary">VIEW</a>
                                        </div>


                                        {{-- WISHLIST SMALL --}}
                                        <div class="wishlist-btn card-wishlist">
                                            <livewire:wishlist-button :product="$product" :wire:key="'wishlist-'.$product->id" />
                                        </div>
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
