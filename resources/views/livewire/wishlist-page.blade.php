<div>
    <div class="wishlist-page mt-100">
        <div class="wishlist-page-inner">
            <div class="container">
                <div class="section-header d-flex align-items-center justify-content-between flex-wrap">
                    <h2 class="section-heading">My Wishlist</h2>
                </div>
                <hr>
                <div class="row">
                    @if(!$products->isEmpty())
                        @foreach($products as $product)
                            <div class="col-lg-3 col-md-6 col-6" data-aos="fade-up" data-aos-duration="700">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <a class="hover-switch" href="{{ url('/products') }}/{{ $product->slug }}">
                                            <img class="primary-img" src="{{ url('storage', $product->images[0]) }}"
                                                 alt="{{ $product->name }}">
                                        </a>

                                        <div class="product-card-action product-card-action-2 justify-content-center">
                                            <div class="action-card action-wishlist">
                                                <livewire:wishlist-button :product="$product" :wire:key="'wishlist-page-'.$product->id" />

                                            </div>

                                            {{--<a href="#" class="action-card action-addtocart">
                                                <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26"
                                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z"
                                                        fill="#00234D" />
                                                </svg>
                                            </a>--}}
                                        </div>
                                    </div>
                                    <div class="product-card-details">
                                        <h3 class="product-card-title">
                                            <a href="{{ url('/products') }}/{{ $product->slug }}">{{ $product->name }}</a>
                                        </h3>
                                        <div class="product-card-price">
                                            <span class="card-price-regular">{{ Number::currency($product->price ?? 0, 'EUR') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12"><p class="text-lg pt-5">Your wishlist is empty.</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
