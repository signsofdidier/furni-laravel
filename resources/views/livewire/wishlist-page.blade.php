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
                                            <img class="primary-img" src="{{ isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('img/product-placeholder.jpg') }}"
                                                 alt="{{  $product->name }}">
                                        </a>

                                        <div class="product-card-action product-card-action-2 justify-content-center">
                                            <div class="action-card action-wishlist">
                                                <livewire:wishlist-button :product="$product" :wire:key="'wishlist-page-'.$product->id" />
                                            </div>
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
                        <div class="col-12"><p class="text-lg">Your wishlist is empty.</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
