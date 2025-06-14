<div>
    <!-- slideshow start -->
    <div class="slideshow-section position-relative">
        <div class="slideshow-active activate-slider" data-slick='{
                    "slidesToShow": 1,
                    "slidesToScroll": 1,
                    "dots": true,
                    "arrows": true,
                    "responsive": [
                        {
                        "breakpoint": 768,
                        "settings": {
                            "arrows": false
                        }
                        }
                    ]
                }'>
            <div class="slide-item slide-item-bag position-relative">
                <img class="slide-img d-none d-md-block" src="{{ asset('assets/img/slideshow/f1-m.jpg') }}" alt="slide-1">
                <img class="slide-img d-md-none" src="{{ asset('assets/img/slideshow/f1-m.jpg') }}" alt="slide-1">
                <div class="content-absolute content-slide">
                    <div class="container height-inherit d-flex align-items-center justify-content-end">
                        <div class="content-box slide-content slide-content-1 py-4">
                            <h2 class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                data-animation="animate__animated animate__fadeInUp">
                                Discover The Best Furniture
                            </h2>
                            <p class="slide-subheading heading_24 animate__animated animate__fadeInUp"
                               data-animation="animate__animated animate__fadeInUp">
                                Look for your inspiration here
                            </p>
                            <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                               href="{{ url('products') }}"
                               data-animation="animate__animated animate__fadeInUp">SHOP
                                NOW</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-item slide-item-bag position-relative">
                <img class="slide-img d-none d-md-block" src="assets/img/slideshow/f2.jpg" alt="slide-2">
                <img class="slide-img d-md-none" src="assets/img/slideshow/f2-m.jpg" alt="slide-2">
                <div class="content-absolute content-slide">
                    <div class="container height-inherit d-flex align-items-center justify-content-end">
                        <div class="content-box slide-content slide-content-1 py-4 text-center">
                            <h2 class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                data-animation="animate__animated animate__fadeInUp">
                                Discover The Best Furniture
                            </h2>
                            <p class="slide-subheading heading_24 animate__animated animate__fadeInUp"
                               data-animation="animate__animated animate__fadeInUp">
                                Look for your inspiration here
                            </p>
                            <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                               href="{{ url('products') }}"
                               data-animation="animate__animated animate__fadeInUp">SHOP
                                NOW</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-item slide-item-bag position-relative">
                <img class="slide-img d-none d-md-block" src="assets/img/slideshow/f3.jpg" alt="slide-3">
                <img class="slide-img d-md-none" src="assets/img/slideshow/f3-m.jpg" alt="slide-3">
                <div class="content-absolute content-slide">
                    <div class="container height-inherit d-flex align-items-center justify-content-center">
                        <div class="content-box slide-content slide-content-1 py-4 text-center">
                            <h2 class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                data-animation="animate__animated animate__fadeInUp">
                                Discover The Best Furniture
                            </h2>
                            <p class="slide-subheading heading_24 animate__animated animate__fadeInUp"
                               data-animation="animate__animated animate__fadeInUp">
                                Look for your inspiration here
                            </p>
                            <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                               href="{{ url('products') }}"
                               data-animation="animate__animated animate__fadeInUp">SHOP
                                NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="activate-arrows"></div>
        <div class="activate-dots dot-tools"></div>
    </div>
    <!-- slideshow end -->

    <!-- trusted badge start -->
    <div class="trusted-section mt-100 overflow-hidden">
        <div class="trusted-section-inner">
            <div class="container">
                <div class="row justify-content-center trusted-row">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="trusted-badge rounded p-0">
                            <div class="trusted-icon">
                                <img class="icon-trusted" src="{{ asset('assets/img/trusted/1.png') }}" alt="icon-1">
                            </div>
                            <div class="trusted-content">
                                <h2 class="heading_18 trusted-heading">Free Shipping & Return</h2>
                                <p class="text_16 trusted-subheading trusted-subheading-2">On all order over
                                    {{ Number::currency($free_shipping_threshold, 'EUR') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="trusted-badge rounded p-0">
                            <div class="trusted-icon">
                                <img class="icon-trusted" src="{{ asset('assets/img/trusted/2.png') }}" alt="icon-2">
                            </div>
                            <div class="trusted-content">
                                <h2 class="heading_18 trusted-heading">Customer Support 24/7</h2>
                                <p class="text_16 trusted-subheading trusted-subheading-2">Instant access to
                                    support</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="trusted-badge rounded p-0">
                            <div class="trusted-icon">
                                <img class="icon-trusted" src="{{ asset('assets/img/trusted/3.png') }}" alt="icon-3">
                            </div>
                            <div class="trusted-content">
                                <h2 class="heading_18 trusted-heading">100% Secure Payment</h2>
                                <p class="text_16 trusted-subheading trusted-subheading-2">We ensure secure
                                    payment!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- trusted badge end -->

    <!-- banner start -->
    <div class="grid-banner mt-100 overflow-hidden">
        <div class="collection-tab-inner mt-0">
            <div class="container">
                <div class="grid-container-2">
                    <a class="grid-item grid-item-1 position-relative rounded mt-0 d-flex" href="{{ url('products') . '?selected_categories[0]=1' }}"
                       data-aos="fade-right" data-aos-duration="700">
                        <img class="banner-img rounded" src="{{ asset('assets/img/banner/f1.jpg') }}" alt="banner-1">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex">
                                <div class="content-box banner-content p-4">
                                    <h2 class="heading_34 primary-color">New <assort></assort> <br>Chairs</h2>
                                    <p class="text_14 mt-2 primary-color">Seats fit for a king.</p>
                                    <span class="text_12 mt-4 link-underline d-block primary-color">
                                                VIEW MORE
                                            </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a class="grid-item grid-item-2 position-relative rounded mt-0 d-flex" href="{{ url('products') . '?selected_categories[0]=3' }}"
                       data-aos="fade-right" data-aos-duration="700">
                        <img class="banner-img rounded" src="{{ asset('assets/img/banner/f3.jpg') }}" alt="banner-1">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex justify-content-end">
                                <div class="content-box banner-content p-4 text-end">
                                    <h2 class="heading_34 primary-color">Bold Tables,<br>Better Living</h2>
                                    <p class="text_14 mt-2 primary-color">Crafted for Everyday Moments</p>
                                    <span class="text_12 mt-4 link-underline d-block primary-color">
                                                VIEW MORE
                                            </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a class="grid-item grid-item-3 position-relative rounded mt-0 d-flex" href="{{ url('products') . '?selected_categories[0]=5' }}"
                       data-aos="fade-left" data-aos-duration="700">
                        <img class="banner-img rounded" src="{{ asset('assets/img/banner/f2.jpg') }}" alt="banner-1">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex">
                                <div class="content-box banner-content p-4">
                                    <h2 class="heading_34 primary-color">Elegant cabinets<br>Modern Living</h2>
                                    <p class="text_14 mt-2 primary-color">Upgrade Your Storage Style</p>
                                    <span class="text_12 mt-4 link-underline d-block primary-color">
                                        VIEW MORE
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <!-- FEATURED PRODUCTS -->
    <div class="featured-collection mt-100 overflow-hidden">
        <div class="collection-tab-inner">
            <div class="container">
                <div class="section-header text-center">
                    <h2 class="section-heading primary-color">Featured Products</h2>
                </div>
                <div class="row">
                    @foreach($featuredProducts as $product)
                        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-duration="700">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <a class="product-hover-zoom" href="{{ url('products') . '/' . $product->slug }}">
                                        <img class="primary-img" src="{{ url('storage', $product->images[0] ) }}"
                                             alt="product-img">
                                    </a>

                                    <div class="product-card-action product-card-action-2 justify-content-center">
                                        {{-- WISHLIST BUTTON --}}
                                        <livewire:wishlist-button :product="$product" :wire:key="'wishlist-'.$product->id" />

                                        {{--Add to cart button--}}
                                       {{-- <button wire:click.prevent='addToCart({{ $product->id }})' type="button" class="action-card action-addtocart">
                                            <svg class="icon icon-cart" width="24" height="26"
                                                 viewBox="0 0 24 26" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z"
                                                    fill="#00234D" />
                                            </svg>
                                        </button>--}}
                                    </div>
                                </div>
                                <div class="product-card-details">
                                    {{-- colors --}}
                                    {{--<ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
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
                                    </ul>--}}
                                    {{-- STAR RATINGS --}}
                                    <livewire:product-rating-page :product="$product" :wire:key="'rating-'.$product->id" />
                                    <h3 class="product-card-title mt-1">
                                        <a href="{{ url('/products') }}/{{ $product->slug }}">Vita Lounge Chair</a>
                                    </h3>
                                    <div class="product-card-price">
                                        <span class="card-price-regular">{{ Number::currency($product->price ?? 0, 'EUR') }}</span>
                                        {{--<span class="card-price-compare text-decoration-line-through">$1759</span>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="view-all text-center" data-aos="fade-up" data-aos-duration="700">
                    <a class="btn-primary" href="{{ url('/products') }}/{{ '?featured=true' }}">VIEW ALL</a>
                </div>
            </div>
        </div>
    </div>
    <!-- collection end -->

    <!-- single banner start -->
    <div class="single-banner-section mt-100 overflow-hidden">
        <div class="position-relative overlay">
            <img class="single-banner-img" src="{{ asset('assets/img/banner/single-banner-2.jpg') }}" alt="slide-1">

            <div class="content-absolute content-slide">
                <div class="container height-inherit d-flex align-items-center justify-content-center">
                    <div class="content-box single-banner-content py-4 text-center" data-aos="fade-up"
                         data-aos-duration="700">
                        <h2 class="single-banner-heading heading_42 text-white animate__animated animate__fadeInUp"
                            data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                            data-aos-duration="700">
                            Created Furniture
                        </h2>
                        <p class="single-banner-text text_16 text-white animate__animated animate__fadeInUp"
                           data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                           data-aos-duration="700">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus.
                        </p>
                        <a class="btn-primary single-banner-btn animate__animated animate__fadeInUp"
                           href="{{ url('/products') }}"
                           data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                           data-aos-duration="700">
                            DISCOVER NOW
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- single banner end -->

    <!-- latest blog start -->
    @if($blogs->count() >= 3)
        <div class="latest-blog-section mt-100 overflow-hidden home-section">
            <div class="latest-blog-inner" >
                <div class="container">
                    <div class="section-header text-center">
                        <h2 class="section-heading primary-color">Latest blogs</h2>
                    </div>
                    <div class="article-card-container position-relative">
                        <div class="common-slider" data-slick='{
                                "slidesToShow": 3,
                                "slidesToScroll": 1,
                                "dots": false,
                                "arrows": true,
                                "responsive": [
                                  {
                                    "breakpoint": 1281,
                                    "settings": {
                                      "slidesToShow": 2
                                    }
                                  },
                                  {
                                    "breakpoint": 602,
                                    "settings": {
                                      "slidesToShow": 1
                                    }
                                  }
                                ]
                            }'>

                            @foreach($blogs as $blog)
                                <div class="article-slick-item" data-aos="fade-up" data-aos-duration="700">
                                    <div class="article-card bg-transparent p-0 shadow-none">
                                        <a class="article-card-img-wrapper" href="{{ route('blog.show', $blog->slug) }}">
                                            <img src="{{ url('storage', $blog->image) }}" alt="{{ $blog->title }}"
                                                 class="article-card-img rounded">

                                                  <span class="article-tag article-tag-absolute rounded">{{ $blog->categories->pluck('name')->join(', ') }}</span>
                                        </a>
                                        <p class="article-card-published text_12 d-flex align-items-center">
                                            <span class="article-date d-flex align-items-center">
                                                <span class="icon-publish">
                                                    <svg width="17" height="18" viewBox="0 0 17 18" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M3.46875 0.875V1.59375H0.59375V17.4063H16.4063V1.59375H13.5313V0.875H12.0938V1.59375H4.90625V0.875H3.46875ZM2.03125 3.03125H3.46875V3.75H4.90625V3.03125H12.0938V3.75H13.5313V3.03125H14.9688V4.46875H2.03125V3.03125ZM2.03125 5.90625H14.9688V15.9688H2.03125V5.90625ZM6.34375 7.34375V8.78125H7.78125V7.34375H6.34375ZM9.21875 7.34375V8.78125H10.6563V7.34375H9.21875ZM12.0938 7.34375V8.78125H13.5313V7.34375H12.0938ZM3.46875 10.2188V11.6563H4.90625V10.2188H3.46875ZM6.34375 10.2188V11.6563H7.78125V10.2188H6.34375ZM9.21875 10.2188V11.6563H10.6563V10.2188H9.21875ZM12.0938 10.2188V11.6563H13.5313V10.2188H12.0938ZM3.46875 13.0938V14.5313H4.90625V13.0938H3.46875ZM6.34375 13.0938V14.5313H7.78125V13.0938H6.34375ZM9.21875 13.0938V14.5313H10.6563V13.0938H9.21875Z"
                                                            fill="#00234D" />
                                                    </svg>
                                                </span>
                                                <span class="ms-2">{{ $blog->updated_at->translatedFormat('d F Y') }}</span>
                                            </span>
                                            <span class="article-author d-flex align-items-center ms-4">
                                                <span class="icon-author"><svg width="15" height="17"
                                                                               viewBox="0 0 15 17" fill="none"
                                                                               xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M7.5 0.59375C4.72888 0.59375 2.46875 2.85388 2.46875 5.625C2.46875 7.3573 3.35315 8.89587 4.69238 9.80274C2.12903 10.9033 0.3125 13.447 0.3125 16.4063H1.75C1.75 13.2224 4.31616 10.6563 7.5 10.6563C10.6838 10.6563 13.25 13.2224 13.25 16.4063H14.6875C14.6875 13.447 12.871 10.9033 10.3076 9.80274C11.6469 8.89587 12.5313 7.3573 12.5313 5.625C12.5313 2.85388 10.2711 0.59375 7.5 0.59375ZM7.5 2.03125C9.49341 2.03125 11.0938 3.63159 11.0938 5.625C11.0938 7.61841 9.49341 9.21875 7.5 9.21875C5.50659 9.21875 3.90625 7.61841 3.90625 5.625C3.90625 3.63159 5.50659 2.03125 7.5 2.03125Z"
                                                            fill="#00234D" />
                                                    </svg>
                                                </span>
                                                <span class="ms-2">{{ $blog->user->name }}</span>
                                            </span>
                                        </p>
                                        <h2 class="article-card-heading heading_18">
                                            <a class="heading_18" href="{{ route('blog.show', $blog->slug) }}">
                                                {{ $blog->title }}
                                            </a>
                                        </h2>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- latest blog end -->
</div>

