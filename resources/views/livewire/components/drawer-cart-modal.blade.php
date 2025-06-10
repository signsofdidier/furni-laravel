<div>
    <!-- drawer cart start -->
    {{--<div class="offcanvas offcanvas-end" tabindex="-1" id="drawer-cart">
        <div class="offcanvas-headertm-black">
            <h5 class="cart-drawer-heading text_16">Your Cart ({{ $total_count }})</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="cart-content-area d-flex justify-content-between flex-column">
                <div class="minicart-loop custom-scrollbar">
                    <!-- minicart item -->
                    @forelse($cart_items as $item)
                        <div class="minicart-item d-flex">
                            <div class="mini-img-wrapper">
                                <img class="mini-img" src="{{ url('storage', $item['image']) }}" alt="{{ $item['name'] }}">
                            </div>
                            <div class="product-info">
                                <h2 class="product-title"><a href="{{ url('/products') }}/{{ $item['slug'] }}">{{ $item['name'] }}</a></h2>
                                --}}{{--<p class="product-vendor">XS / Dove Gray</p>--}}{{--
                                <div class="misc d-flex align-items-end justify-content-between">
                                    <div class="quantity d-flex align-items-center justify-content-between">
                                        --}}{{--DECREASE--}}{{--
                                        <button
                                            wire:click.prevent="decreaseQuantity({{ $item['product_id'] }})"
                                            class="qty-btn dec-qty">
                                            <img src="{{ asset('assets/img/icon/minus.svg') }}" alt="minus">
                                        </button>

                                        --}}{{--QUANTITY--}}{{--
                                        --}}{{--<input class="qty-input" type="number" name="qty" value="1" min="0">--}}{{--
                                        <span class="text-center w-8">{{ $item['quantity'] }}</span>

                                        --}}{{--INCREASE--}}{{--
                                        <button
                                            wire:click.prevent="increaseQuantity({{ $item['product_id'] }})"
                                            class="qty-btn inc-qty">
                                            <img src="{{ asset('assets/img/icon/plus.svg') }}" alt="plus">
                                        </button>
                                    </div>
                                    <div class="product-remove-area d-flex flex-column align-items-end">
                                        --}}{{-- !!!!!!!!!!!!!!!!  veranderen in subtotal--}}{{--
                                        --}}{{-- SUBTOTAL --}}{{--
                                        <div class="product-price">{{ Number::currency($item['total_amount'], 'EUR') }}</div>

                                        --}}{{--REMOVE ITEM--}}{{--
                                        <button
                                            type="button"
                                            wire:click.prevent="removeItem({{ $item['product_id'] }})"
                                            class="product-remove btn btn-link p-0">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <h2>There are no items in the cart.</h2>
                    @endforelse

                </div>
                <div class="minicart-footer">
                    <div class="minicart-calc-area">
                        <div class="minicart-calc d-flex align-items-center justify-content-between">
                            <span class="cart-subtotal mb-0">Subtotal</span>
                            <span class="cart-subprice">{{ Number::currency($grand_total, 'EUR') }}</span>
                        </div>
                        <p class="cart-taxes text-center my-4">Taxes and shipping will be calculated at checkout.
                        </p>
                    </div>
                    <div class="minicart-btn-area d-flex align-items-center justify-content-between">
                        <a href="{{ url('/cart') }}" class="minicart-btn btn-secondary">View Cart</a>
                        <a href="{{ url('/checkout') }}" class="minicart-btn btn-primary">Checkout</a>
                    </div>
                </div>
            </div>
            <div class="cart-empty-area text-center py-5 d-none">
                <div class="cart-empty-icon pb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                </div>
                <p class="cart-empty">You have no items in your cart</p>
            </div>
        </div>
    </div>--}}

    <flux:modal name="cart" variant="flyout" position="right" max-width="lg" class="!ml-auto !mr-0 h-screen">
        <div class="h-full flex flex-col">
            {{-- Header --}}
            <div class="border-b border-gray-200 py-2">
                <h5 class="text-lg font-bold mb-3">Your Cart ({{ $total_count }})</h5>

                {{-- CART CLEAR --}}
                @if($cart_items)
                    <button
                        x-data
                        @click.prevent="if (confirm('Are you sure you want to clear the cart?')) { $wire.clearCart() }"
                        class="w-full py-2 text-sm text-red-600 border border-red-600 rounded hover:bg-red-50">
                        Clear Cart
                    </button>
                @endif


            </div>

            {{-- Scrollbare inhoud --}}
            <div class="flex-1 min-h-0 overflow-y-auto px-1 py-4 space-y-2 hide-scrollbar">
                @forelse($cart_items as $item)
                    <div class="flex items-start mb-2 space-x-4">
                        <img src="{{ url('storage', $item['image']) }}" class="w-14 h-14 object-cover rounded" alt="{{ $item['name'] }}">

                        <div class="flex-1 text-sm">
                            <a href="{{ url('/products') }}/{{ $item['slug'] }}" class="font-semibold text-sm !text-gray-900">{{ $item['name'] }}</a>
                            <div class="text-gray-500 text-xs">{{ $item['variant'] ?? 'XS / Dove Gray' }}</div>

                            <div class="flex justify-between items-center mt-2">
                                <div class="flex items-center gap-1">
                                    <button wire:click="decreaseQuantity({{ $item['product_id'] }})"
                                            class="w-9 h-9 rounded border border-gray-300 text-lg leading-none flex items-center justify-center hover:bg-gray-100">−</button>
                                    <span class="text-sm w-6 text-center">{{ $item['quantity'] }}</span>
                                    <button wire:click="increaseQuantity({{ $item['product_id'] }})"
                                            class="w-9 h-9 rounded border border-gray-300 text-lg leading-none flex items-center justify-center hover:bg-gray-100">+</button>
                                </div>

                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        {{ Number::currency($item['total_amount'], 'EUR') }}
                                    </div>
                                    <button wire:click="removeItem({{ $item['product_id'] }})"
                                            class="text-xs text-red-600 hover:underline mt-0.5">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">There are no items in the cart.</p>
                @endforelse
            </div>

            {{-- Footer --}}
            @if(count($cart_items))
                <div class="border-t border-gray-200 pt-4 flex-shrink-0">
                    <div class="flex justify-between items-center text-sm font-semibold">
                        <span>Subtotal</span>
                        <span>{{ Number::currency($grand_total, 'EUR') }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Taxes and shipping will be calculated at checkout.</p>



                    <div class="mt-2 flex gap-2">
                        <a href="/cart"
                           class="flex-1 text-center py-2 border text-sm rounded hover:bg-gray-50">View Cart</a>
                        <a href="/checkout"
                           class="flex-1 text-center py-2 bg-[#00234D] text-white text-sm rounded hover:bg-[#00152F]">Checkout</a>
                    </div>
                </div>
            @endif
        </div>
    </flux:modal>

    <!-- drawer cart end -->

    <style>
        .hide-scrollbar {
            scrollbar-width: none;            /* Firefox */
            -ms-overflow-style: none;         /* IE 10+ */
        }

        .hide-scrollbar::-webkit-scrollbar { /* WebKit */
            width: 0px;
            height: 0px;
        }
    </style>


</div>
