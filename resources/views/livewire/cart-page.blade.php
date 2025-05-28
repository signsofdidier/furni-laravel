<div>
    <div class="cart-page mt-100">
        <div class="container">
            <div class="cart-page-wrapper">
                <div class="row">
                    <div class="col-lg-7 col-md-12 col-12">
                        @if($cart_items)
                            <table class="cart-table w-100">
                                <thead>
                                <tr>
                                    <th class="cart-caption heading_18">Product</th>
                                    <th class="cart-caption heading_18"></th>
                                    <th class="cart-caption text-center heading_18 d-none d-md-table-cell">Quantity</th>
                                    <th class="cart-caption text-end heading_18">Price</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($cart_items as $item)
                                    <tr class="cart-item">
                                        <td class="cart-item-media">
                                            <div class="mini-img-wrapper">
                                                <img class="mini-img" src="{{ url('storage', $item['image']) }}" alt="{{ $item['name'] }}">
                                            </div>
                                        </td>
                                        <td class="cart-item-details">
                                            <h2 class="product-title"><a href="/products/{{ $item['slug'] }}">{{ $item['name'] }}</a></h2>

                                            {{-- COLOR --}}
                                            <p class="product-vendor">Dove Gray</p>
                                        </td>
                                        <td class="cart-item-quantity d-md-flex flex-md-column">
                                            <div class="quantity d-flex align-items-center justify-content-between">
                                                {{-- DECREMENT --}}
                                                <button wire:click="decreaseQuantity({{ $item['product_id'] }})" class="qty-btn dec-qty">
                                                    <img src="{{ asset('assets/img/icon/minus.svg') }}" alt="minus">
                                                </button>

                                                {{-- QUANTITY --}}
                                                {{--<input class="qty-input" type="number" name="qty" value="1" min="0">--}}
                                                <span class="qty-input">{{ $item['quantity'] }}</span>

                                                {{-- INCREMENT --}}
                                                <button wire:click="increaseQuantity({{ $item['product_id'] }})" class="qty-btn inc-qty">
                                                    <img src="{{ asset('assets/img/icon/plus.svg') }}" alt="plus">
                                                </button>
                                            </div>
                                            <button wire:click="removeItem({{ $item['product_id'] }})" type="button" class="product-remove mt-2 text-danger">Remove
                                            </button>
                                        </td>
                                        <td class="cart-item-price text-end">
                                            <div class="product-price">{{ Number::currency($item['total_amount'], 'EUR') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <h4>There are no items in your cart</h4>
                        @endif
                    </div>
                    <div class="col-lg-5 col-md-12 col-12">
                        <div class="cart-total-area">
                            <h3 class="cart-total-title d-none d-lg-block mb-0">Cart Totals</h3>
                                <div class="cart-total-box mt-4">
                                    <div class="subtotal-item subtotal-box">
                                        <h4 class="subtotal-title">Subtotals:</h4>
                                        <p class="subtotal-value">{{ Number::currency($grand_total, 'EUR') }}</p>
                                    </div>
                                    <div class="subtotal-item shipping-box">
                                        <h4 class="subtotal-title">Shipping:</h4>
                                        <p class="subtotal-value">{{ Number::currency(0, 'EUR') }}</p>
                                    </div>
                                    <div class="subtotal-item discount-box">
                                        <h4 class="subtotal-title">Discount:</h4>
                                        <p class="subtotal-value">{{ Number::currency(0, 'EUR') }}</p>
                                    </div>
                                    <hr />
                                    <div class="subtotal-item discount-box">
                                        <h4 class="subtotal-title">Total:</h4>
                                        <p class="subtotal-value">{{ Number::currency($grand_total, 'EUR') }}</p>
                                    </div>
                                    <p class="shipping_text">Shipping & taxes calculated at checkout</p>
                                    <div class="d-flex justify-content-center mt-4">
                                        @if($cart_items)
                                            <a href="/checkout" class="position-relative btn-primary text-uppercase">
                                                Proceed to checkout
                                            </a>
                                        @else
                                            <p disabled class="position-relative btn-primary text-uppercase">
                                                No items in cart
                                            </p>
                                        @endif
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--
<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th class="text-left font-semibold">Product</th>
                            <th class="text-left font-semibold">Price</th>
                            <th class="text-left font-semibold">Quantity</th>
                            <th class="text-left font-semibold">Total</th>
                            <th class="text-left font-semibold">Remove</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($cart_items as $item)
                            <tr wire:key="{{ $item['product_id'] }}">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 mr-4" src="{{ url('storage', $item['image']) }}" alt="{{ $item['name'] }}">
                                        <span class="font-semibold">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4">{{ Number::currency($item['unit_amount'], 'EUR') }}</td>
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <button
                                            wire:click="decreaseQuantity({{ $item['product_id'] }})"
                                            class="border rounded-md py-2 px-4 mr-2 bg-gray-100 hover:bg-gray-200 transition-colors duration-150 cursor-pointer"
                                            title="Decrease quantity"
                                        >
                                            –
                                        </button>
                                        <span class="text-center w-8">{{ $item['quantity'] }}</span>
                                        <button
                                            wire:click="increaseQuantity({{ $item['product_id'] }})"
                                            class="border rounded-md py-2 px-4 ml-2 bg-gray-100 hover:bg-gray-200 transition-colors duration-150 cursor-pointer"
                                            title="Increase quantity"
                                        >
                                            +
                                        </button>
                                    </div>
                                </td>

                                <td class="py-4">{{ Number::currency($item['total_amount'], 'EUR') }}</td>
                                <td>
                                    <button wire:click="removeItem({{ $item['product_id'] }})" class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700"><span wire:loading.remove wire:target="removeItem({{ $item['product_id'] }})">Remove</span><span wire:loading wire:target="removeItem({{ $item['product_id'] }})">Trashing</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-2xl font-semibold text-slate-500">
                                    There are no items in the cart.
                                </td>
                            </tr>
                        @endforelse

                        <!-- More product rows -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ Number::currency($grand_total, 'EUR') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Taxes</span>
                        <span>{{ Number::currency(0, 'EUR') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        <span>{{ Number::currency(0, 'EUR') }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Grand Total</span>
                        <span class="font-semibold"><span>{{ Number::currency($grand_total, 'EUR') }}</span></span>
                    </div>

                    @if($cart_items)
                        <a href="/checkout" class="bg-blue-500 block text-white text-center py-2 px-4 rounded-lg mt-4 w-full">Checkout</a>
                    @else
                        <a href="/checkout" class="bg-gray-300 block text-white text-center py-2 px-4 rounded-lg mt-4 w-full" disabled>Checkout</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
--}}
