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
                                            <h2 class="product-title"><a href="{{ url('/products') }}/{{ $item['slug'] }}">{{ $item['name'] }}</a></h2>

                                            {{-- COLOR --}}
                                            @if(! empty($item['color_name']))
                                                <p class="product-vendor d-flex align-items-center">
                                                    <span
                                                        class="me-2 rounded-circle"
                                                        style="
                                                          display:inline-block;
                                                          width:1rem;
                                                          height:1rem;
                                                          background-color: {{ $item['color_hex'] }};
                                                          border: 1px solid #ccc;
                                                        ">
                                                    </span>
                                                    {{ $item['color_name'] }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="cart-item-quantity d-md-flex flex-md-column">
                                            <div class="quantity d-flex align-items-center justify-content-between">
                                                {{-- DECREMENT --}}
                                                <button wire:click="decreaseQuantity({{ $item['product_id'] }}, {{ $item['color_id'] }})" class="qty-btn dec-qty">
                                                    <img src="{{ asset('assets/img/icon/minus.svg') }}" alt="minus">
                                                </button>

                                                {{-- QUANTITY --}}
                                                {{--<input class="qty-input" type="number" name="qty" value="1" min="0">--}}
                                                <span class="qty-input">{{ $item['quantity'] }}</span>

                                                {{-- INCREMENT --}}
                                                <button wire:click="increaseQuantity({{ $item['product_id'] }}, {{ $item['color_id'] }})" class="qty-btn inc-qty">
                                                    <img src="{{ asset('assets/img/icon/plus.svg') }}" alt="plus">
                                                </button>
                                            </div>
                                            <button wire:click="removeItem({{ $item['product_id'] }}, {{ $item['color_id'] }})" type="button" class="product-remove mt-2 text-danger">Remove
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
                                            <a href="{{ url('/checkout') }}" class="position-relative btn-primary text-uppercase">
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
