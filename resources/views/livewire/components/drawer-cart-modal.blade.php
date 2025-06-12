<div>
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

                            {{-- COLOR IN CART --}}
                            @if(!empty($item['color_name']) && !empty($item['color_hex']))
                                <div class="text-gray-500 text-xs flex items-center gap-1">
                                    <span
                                        class="inline-block w-3 h-3 rounded-full border border-gray-300"
                                        style="background-color: {{ $item['color_hex'] }};"
                                        title="{{ $item['color_name'] }}"
                                    ></span>
                                    {{ $item['color_name'] }}
                                </div>
                            @else
                                <div class="text-gray-400 text-xs">No color selected</div>
                            @endif


                            <div class="flex justify-between items-center mt-2">
                                <div class="flex items-center gap-1">
                                    <button wire:click="decreaseQuantity({{ $item['product_id'] }}, {{ $item['color_id'] ?? 'null' }})"
                                            class="w-9 h-9 rounded border border-gray-300 text-lg leading-none flex items-center justify-center hover:bg-gray-100">−</button>

                                    <span class="text-sm w-6 text-center">{{ $item['quantity'] }}</span>

                                    <button wire:click="increaseQuantity({{ $item['product_id'] }}, {{ $item['color_id'] ?? 'null' }})"
                                            class="w-9 h-9 rounded border border-gray-300 text-lg leading-none flex items-center justify-center hover:bg-gray-100">+</button>
                                </div>

                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        {{ Number::currency($item['total_amount'], 'EUR') }}
                                    </div>
                                    <button wire:click="removeItem({{ $item['product_id'] }}, {{ $item['color_id'] ?? 'null' }})"
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
