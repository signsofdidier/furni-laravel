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
                                            <img src="{{ url('storage', $image) }}" alt="img">
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
                        <div class="product-rating d-flex align-items-center mb-3">
                                    <span class="star-rating d-flex">
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#FFAE00"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#FFAE00"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#FFAE00"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#FFAE00"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#B2B2B2"/>
                                        </svg>
                                    </span>
                            <span class="rating-count ms-2">(22)</span>
                        </div>
                        <div class="product-price-wrapper mb-4">
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

                        {{-- COLORS --}}
                        <div class="product-variant-wrapper">
                            <div class="product-variant product-variant-color">
                                <strong class="label mb-1 d-block">Color:</strong>

                                <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                                    @foreach($product->colors as $color)
                                        <li class="variant-item">
                                            <input
                                                type="radio"
                                                id="color-{{ $color->id }}"
                                                name="selectedColorId"
                                                value="{{ $color->id }}"
                                                wire:model="selectedColorId"
                                                class="visually-hidden"
                                                checked
                                            >
                                            <label
                                                for="color-{{ $color->id }}"
                                                class="variant-label rounded-circle d-inline-block"
                                                style="
                                                    width: 1.5rem;
                                                    height: 1.5rem;
                                                    background-color: {{ $color->hex }};
                                                    border: 1px solid #ccc;
                                                    box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
                                                    cursor: pointer;
                                                "
                                                title="{{ $color->name }}"
                                            >
                                            </label>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>

                        <div class="misc d-flex align-items-end justify-content-between mt-4">
                            <div class="quantity d-flex align-items-center justify-content-between">
                                {{-- INCREMENT --}}
                                <button wire:click="decreaseQuantity" class="qty-btn dec-qty"><img src="{{ asset('assets/img/icon/minus.svg') }}" alt="minus"></button>
                                <input wire:model="quantity" class="qty-input" type="number" name="qty" value="1" min="0">
                                {{-- INCREMENT --}}
                                <button wire:click="increaseQuantity" class="qty-btn inc-qty"><img src="{{ asset('assets/img/icon/plus.svg') }}" alt="plus"></button>
                            </div>
                        </div>

                        <form class="product-form" action="#">
                            <div class="product-form-buttons d-flex align-items-center justify-content-between mt-4">

                                {{-- ADD TO CART --}}
                                <button wire:click="addToCart({{ $product->id }})" type="button" class="position-relative btn-atc btn-add-to-cart loader">
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

                        <div class="guaranteed-checkout">
                            <strong class="label mb-1 d-block">Guaranteed safe checkout:</strong>
                            <ul class="list-unstyled checkout-icon-list d-flex align-items-center flex-wrap">
                                <li class="checkout-icon-item">
                                    <svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_205_2246)">
                                            <path opacity="0.07" d="M35 0H3C1.3 0 0 1.3 0 3V21C0 22.7 1.4 24 3 24H35C36.7 24 38 22.7 38 21V3C38 1.3 36.6 0 35 0Z" fill="black"/>
                                            <path d="M35 1C36.1 1 37 1.9 37 3V21C37 22.1 36.1 23 35 23H3C1.9 23 1 22.1 1 21V3C1 1.9 1.9 1 3 1H35Z" fill="#FEFEFE"/>
                                            <path d="M15 19C18.866 19 22 15.866 22 12C22 8.13401 18.866 5 15 5C11.134 5 8 8.13401 8 12C8 15.866 11.134 19 15 19Z" fill="#EB001B"/>
                                            <path d="M23 19C26.866 19 30 15.866 30 12C30 8.13401 26.866 5 23 5C19.134 5 16 8.13401 16 12C16 15.866 19.134 19 23 19Z" fill="#F79E1B"/>
                                            <path d="M22 12C22 9.59999 20.8 7.49999 19 6.29999C17.2 7.59999 16 9.69999 16 12C16 14.3 17.2 16.5 19 17.7C20.8 16.5 22 14.4 22 12Z" fill="#FF5F00"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_205_2246">
                                                <rect width="38" height="24" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </li>
                                <li class="checkout-icon-item">
                                    <svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_205_2252)">
                                            <path opacity="0.07" d="M35 0H3C1.3 0 0 1.3 0 3V21C0 22.7 1.4 24 3 24H35C36.7 24 38 22.7 38 21V3C38 1.3 36.6 0 35 0Z" fill="black"/>
                                            <path d="M35 1C36.1 1 37 1.9 37 3V21C37 22.1 36.1 23 35 23H3C1.9 23 1 22.1 1 21V3C1 1.9 1.9 1 3 1H35Z" fill="#FEFEFE"/>
                                            <path d="M23.9 8.3C24.1 7.3 23.9 6.6 23.3 6C22.7 5.3 21.6 5 20.2 5H16.1C15.8 5 15.6 5.2 15.5 5.5L14 15.6C14 15.8 14.1 16 14.3 16H17L17.4 12.6L19.2 10.4L23.9 8.3Z" fill="#003087"/>
                                            <path d="M23.8996 8.29999L23.6996 8.49999C23.1996 11.3 21.4996 12.3 19.0996 12.3H17.9996C17.6996 12.3 17.4996 12.5 17.3996 12.8L16.7996 16.7L16.5996 17.7C16.5996 17.9 16.6996 18.1 16.8996 18.1H18.9996C19.2996 18.1 19.4996 17.9 19.4996 17.7V17.6L19.8996 15.2V15.1C19.8996 14.9 20.1996 14.7 20.3996 14.7H20.6996C22.7996 14.7 24.3996 13.9 24.7996 11.5C24.9996 10.5 24.8996 9.69999 24.3996 9.09999C24.2996 8.59999 24.0996 8.39999 23.8996 8.29999Z" fill="#3086C8"/>
                                            <path d="M23.3004 8.09999C23.2004 7.99999 23.1004 7.99999 23.0004 7.99999C22.9004 7.99999 22.8004 7.99999 22.7004 7.89999C22.4004 7.79999 22.0004 7.79999 21.6004 7.79999H18.6004C18.5004 7.79999 18.4004 7.79999 18.4004 7.89999C18.2004 7.99999 18.1004 8.09999 18.1004 8.29999L17.4004 12.7V12.8C17.4004 12.5 17.7004 12.3 18.0004 12.3H19.3004C21.8004 12.3 23.4004 11.3 23.9004 8.49999V8.29999C23.8004 8.19999 23.6004 8.09999 23.4004 8.09999H23.3004Z" fill="#012169"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_205_2252">
                                                <rect width="38" height="24" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </li>
                                <li class="checkout-icon-item">
                                    <svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_205_2274)">
                                            <path opacity="0.07" d="M35 0H3C1.3 0 0 1.3 0 3V21C0 22.7 1.4 24 3 24H35C36.7 24 38 22.7 38 21V3C38 1.3 36.6 0 35 0Z" fill="black"/>
                                            <path d="M35 1C36.1 1 37 1.9 37 3V21C37 22.1 36.1 23 35 23H3C1.9 23 1 22.1 1 21V3C1 1.9 1.9 1 3 1H35Z" fill="#FEFEFE"/>
                                            <path d="M28.3 10.1H28C27.6 11.1 27.3 11.6 27 13.1H28.9C28.6 11.6 28.6 10.9 28.3 10.1ZM31.2 16H29.5C29.4 16 29.4 16 29.3 15.9L29.1 15L29 14.8H26.6C26.5 14.8 26.4 14.8 26.4 15L26.1 15.9C26.1 16 26 16 26 16H23.9L24.1 15.5L27 8.7C27 8.2 27.3 8 27.8 8H29.3C29.4 8 29.5 8 29.5 8.2L30.9 14.7C31 15.1 31.1 15.4 31.1 15.8C31.2 15.9 31.2 15.9 31.2 16ZM17.8 15.7L18.2 13.9C18.3 13.9 18.4 14 18.4 14C19.1 14.3 19.8 14.5 20.5 14.4C20.7 14.4 21 14.3 21.2 14.2C21.7 14 21.7 13.5 21.3 13.1C21.1 12.9 20.8 12.8 20.5 12.6C20.1 12.4 19.7 12.2 19.4 11.9C18.2 10.9 18.6 9.5 19.3 8.8C19.9 8.4 20.2 8 21 8C22.2 8 23.5 8 24.1 8.2H24.2C24.1 8.8 24 9.3 23.8 9.9C23.3 9.7 22.8 9.5 22.3 9.5C22 9.5 21.7 9.5 21.4 9.6C21.2 9.6 21.1 9.7 21 9.8C20.8 10 20.8 10.3 21 10.5L21.5 10.9C21.9 11.1 22.3 11.3 22.6 11.5C23.1 11.8 23.6 12.3 23.7 12.9C23.9 13.8 23.6 14.6 22.8 15.2C22.3 15.6 22.1 15.8 21.4 15.8C20 15.8 18.9 15.9 18 15.6C17.9 15.8 17.9 15.8 17.8 15.7ZM14.3 16C14.4 15.3 14.4 15.3 14.5 15C15 12.8 15.5 10.5 15.9 8.3C16 8.1 16 8 16.2 8H18C17.8 9.2 17.6 10.1 17.3 11.2C17 12.7 16.7 14.2 16.3 15.7C16.3 15.9 16.2 15.9 16 15.9L14.3 16ZM5 8.2C5 8.1 5.2 8 5.3 8H8.7C9.2 8 9.6 8.3 9.7 8.8L10.6 13.2C10.6 13.3 10.6 13.3 10.7 13.4C10.7 13.3 10.8 13.3 10.8 13.3L12.9 8.2C12.8 8.1 12.9 8 13 8H15.1C15.1 8.1 15.1 8.1 15 8.2L11.9 15.5C11.8 15.7 11.8 15.8 11.7 15.9C11.6 16 11.4 15.9 11.2 15.9H9.7C9.6 15.9 9.5 15.9 9.5 15.7L7.9 9.5C7.7 9.3 7.4 9 7 8.9C6.4 8.6 5.3 8.4 5.1 8.4L5 8.2Z" fill="#FFD200"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_205_2274">
                                                <rect width="38" height="24" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </li>
                            </ul>
                        </div>

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
                    <a class="product-tab-link tab-link active" href="#pdescription" data-bs-toggle="tab">Description</a>
                    <a class="product-tab-link tab-link" href="#pshipping" data-bs-toggle="tab">Shipping & Returns</a>
                    <a class="product-tab-link tab-link" href="#pstyle" data-bs-toggle="tab">Style with</a>
                    <a class="product-tab-link tab-link" href="#preview" data-bs-toggle="tab">Reviews</a>
                </nav>
            </div>
            <div class="tab-content product-tab-content">
                <div id="pdescription" class="tab-pane fade show active">
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-12">
                            <div class="desc-content">
                                <h4 class="heading_18 mb-3">{{ $product->name }}</h4>
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
                            </div>
                        </div>
                    </div>
                </div>
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
                <div id="preview" class="tab-pane fade">
                    <div class="review-area accordion-parent">
                        <h4 class="heading_18 mb-3">Customer Reviews</h4>
                        <div class="review-header d-flex justify-content-between align-items-center">
                            <p class="text_16">No reviews yet.</p>
                            <button class="text_14 bg-transparent text-decoration-underline write-btn" type="button">Write a review</button>
                        </div>
                        <div class="review-form-area accordion-child">
                            <form action="#">
                                <fieldset>
                                    <label class="label">Full Name</label>
                                    <input type="text" placeholder="Enter your name" />
                                </fieldset>
                                <fieldset>
                                    <label class="label">Email</label>
                                    <input type="email" placeholder="john.smith@example.com" />
                                </fieldset>
                                <fieldset>
                                    <label class="label">Rating</label>
                                    <div class="star-rating">
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#B2B2B2"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#B2B2B2"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#B2B2B2"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#B2B2B2"/>
                                        </svg>
                                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.168 5.77344L10.082 5.23633L8 0.566406L5.91797 5.23633L0.832031 5.77344L4.63086 9.19727L3.57031 14.1992L8 11.6445L12.4297 14.1992L11.3691 9.19727L15.168 5.77344Z" fill="#B2B2B2"/>
                                        </svg>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <label class="label">Review Title</label>
                                    <input type="text" placeholder="Give your review a title" />
                                </fieldset>
                                <fieldset>
                                    <label class="label">Body of Review (2000)</label>
                                    <textarea cols="30" rows="10" placeholder="Write your comments here"></textarea>
                                </fieldset>

                                <button type="submit" class="position-relative review-submit-btn">SUBMIT</button>
                            </form>
                        </div>
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
                                    <div class="product-card-img">
                                        <a class="product-hover-zoom" href="{{ url('/products') }}/{{ $featured->slug }}">
                                            <img class="primary-img" src="{{ url('storage', $featured->images[0]) }}"
                                                 alt="product-img">
                                        </a>

                                        <div class="product-card-action product-card-action-2">
                                            <a href="{{ url('/products') }}/{{ $featured->slug }}" class="quickview-btn btn-primary">VIEW</a>
                                            <button wire:click.prevent="addToCart({{ $featured->id }})" type="button" class="addtocart-btn btn-primary">
                                                ADD TO CART
                                            </button>
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
                                    <div class="product-card-details text-center">
                                        <h3 class="product-card-title"><a href="{{ url('/products') }}/{{ $featured->slug }}">{{ $featured->name }}</a>
                                        </h3>
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
</div>
