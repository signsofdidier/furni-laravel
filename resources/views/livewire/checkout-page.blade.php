<div>
    <div class="checkout-page mt-100">
        <div class="container">
            <div class="checkout-page-wrapper">
                <div class="row">
                    <div class="col-xl-9 col-lg-8 col-md-12 col-12">
                        <div class="section-header mb-3">
                            <h2 class="section-heading">Check out</h2>
                        </div>

                        <div class="checkout-user-area overflow-hidden d-flex align-items-center">
                            <div class="checkout-user-img me-4">
                                <img class="rounded-circle" style="height: 80px; width: 80px;" src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : asset('assets/img/default-avatar.png') }}" alt="{{ Auth::user()->name }}">
                            </div>
                            <div class="checkout-user-details d-flex align-items-center justify-content-between w-100">
                                <div class="checkout-user-info">
                                    <h2 class="checkout-user-name">{{ Auth::user()->name }}</h2>
                                    <p class="text-light mb-0">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="edit-user btn">EDIT PROFILE</a>
                            </div>
                        </div>

                        <div class="shipping-address-area">
                            <h2 class="shipping-address-heading pb-1">Shipping address</h2>
                            <div class="shipping-address-form-wrapper">
                                <form wire:submit.prevent="placeOrder" class="shipping-address-form common-form">
                                    <div class="row">
                                        {{-- Adressen keuze blok --}}
                                        @if($addresses && $addresses->count())
                                            <div class="col-12 mb-4">
                                                <label class="form-label fw-semibold">Kies een opgeslagen adres:</label>
                                                <div class="address-selection-wrapper">
                                                    @foreach($addresses as $address)
                                                        <div class="address-option mb-2">
                                                            <input class="form-check-input"
                                                                   type="radio"
                                                                   wire:model.live="selected_address_id"
                                                                   value="{{ $address->id }}"
                                                                   id="address-{{ $address->id }}">
                                                            <label for="address-{{ $address->id }}" class="address-label">
                                                                <div class="address-info">
                                                                    <strong>{{ $address->first_name }} {{ $address->last_name }}</strong><br>
                                                                    <span class="text-muted">{{ $address->street_address }}, {{ $address->city }} {{ $address->zip_code }}</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                    <div class="address-option">
                                                        <input class="form-check-input"
                                                               type="radio"
                                                               wire:model.live="selected_address_id"
                                                               value="new"
                                                               id="address-new">
                                                        <label for="address-new" class="address-label">
                                                            <div class="address-info">
                                                                <strong>Nieuw adres toevoegen</strong><br>
                                                                <span class="text-muted">Voer een nieuw verzendadres in</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Alleen adresvelden tonen als nieuw adres gekozen is (of geen adressen) --}}
                                        @if($selected_address_id == 'new' || !$addresses || !$addresses->count())
                                            <div class="new-address-fields">
                                                <div class="row">
                                                    {{-- First Name --}}
                                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                                        <label for="first_name" class="form-label">First name</label>
                                                        <input wire:model="first_name" id="first_name" type="text" class="form-control" />
                                                        @error('first_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                                    </div>
                                                    {{-- Last Name --}}
                                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                                        <label for="last_name" class="form-label">Last name</label>
                                                        <input wire:model="last_name" id="last_name" type="text" class="form-control" />
                                                        @error('last_name') <div class="text-danger small">{{ $message }}</div> @enderror
                                                    </div>
                                                    {{-- Phone --}}
                                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                                        <label for="phone" class="form-label">Phone</label>
                                                        <input wire:model="phone" id="phone" type="text" class="form-control" />
                                                        @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
                                                    </div>
                                                    {{-- Street Address --}}
                                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                                        <label for="street_address" class="form-label">Address</label>
                                                        <input wire:model="street_address" id="street_address" type="text" class="form-control" />
                                                        @error('street_address') <div class="text-danger small">{{ $message }}</div> @enderror
                                                    </div>
                                                    {{-- City --}}
                                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                                        <label for="city" class="form-label">City</label>
                                                        <input wire:model="city" id="city" type="text" class="form-control" />
                                                        @error('city') <div class="text-danger small">{{ $message }}</div> @enderror
                                                    </div>
                                                    {{-- Zip Code --}}
                                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                                        <label for="zip_code" class="form-label">Zip code</label>
                                                        <input wire:model="zip_code" id="zip_code" type="text" class="form-control" />
                                                        @error('zip_code') <div class="text-danger small">{{ $message }}</div> @enderror
                                                    </div>
                                                    {{-- State --}}
                                                    <div class="col-lg-6 col-md-12 col-12 mb-3">
                                                        <label for="state" class="form-label">State</label>
                                                        <input wire:model="state" id="state" type="text" class="form-control" />
                                                        @error('state') <div class="text-danger small">{{ $message }}</div> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Country --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label for="country" class="label">Country</label>
                                                <input type="text" class="form-control" value="Belgium" disabled readonly>
                                            </fieldset>
                                        </div>

                                        {{--  Payment Method --}}
                                        <div class="col-lg-6 col-md-12 col-12">
                                            <fieldset>
                                                <label class="label">Payment method</label>
                                                <div class="d-flex gap-2">
                                                    <input type="radio" id="payment-cod" wire:model="payment_method" value="cod" class="btn-check">
                                                    <label for="payment-cod" class="btn btn-outline-secondary flex-fill">CASH ON DELIVERY</label>
                                                    <input type="radio" id="payment-stripe" wire:model="payment_method" value="stripe" class="btn-check">
                                                    <label for="payment-stripe" class="btn btn-outline-secondary flex-fill">BANCONTACT</label>
                                                </div>
                                            </fieldset>
                                            @error('payment_method')
                                            <div class="text-danger small">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <div class=" shipping-address-area billing-area mt-4">
                                            <div class="minicart-btn-area d-flex align-items-center justify-content-between flex-wrap">
                                                <a href="{{ url('/cart') }}" class="checkout-page-btn minicart-btn btn-secondary">BACK TO CART</a>
                                                <button type="submit" class="checkout-page-btn minicart-btn btn-primary">PLACE ORDER</button>
                                            </div>
                                        </div>

                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>


                    {{-- ORDER SUMMARY --}}
                    <div class="col-xl-3 col-lg-4 col-md-12 col-12">
                        <div class="cart-total-area checkout-summary-area">
                            <h3 class="d-none d-lg-block mb-0 text-center heading_24 mb-4">Order summary</h3>

                            @forelse($cart_items as $item)
                                <div class="minicart-item d-flex">
                                    <div class="mini-img-wrapper">
                                        <img class="mini-img" src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    </div>
                                    <div class="product-info">
                                        <h2 class="product-title">
                                            <a href="{{ url('/products') }}/{{ $item['slug'] }}">
                                                {{ $item['name'] }}
                                            </a>
                                        </h2>

                                        {{-- COLOR --}}
                                        @if(! empty($item['color_name']))
                                            <p class="product-vendor d-flex align-items-center">
                                                    <span
                                                        class="me-2 rounded-circle"
                                                        style="
                                                          display:inline-block;
                                                          width:0.7rem;
                                                          height:0.7rem;
                                                          background-color: {{ $item['color_hex'] }};
                                                          border: 1px solid #ccc;
                                                        ">
                                                    </span>
                                                {{ $item['color_name'] }}
                                            </p>
                                        @endif

                                        <p class="product-vendor mb-1">
                                            {{ Number::currency($item['unit_amount'], 'EUR') }} × {{ $item['quantity'] }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                {{-- Als je om de een of andere reden geen items hebt… (maar dat zou niet mogen) --}}
                            @endforelse

                            {{-- Veld voor kortingscode (optioneel) --}}
                            {{--<div class="mb-4">
                                <label for="discount_code" class="form-label">Discount code:</label>
                                <input
                                    type="text"
                                    wire:model.defer="discount_code"
                                    id="discount_code"
                                    class="form-control @error('discount_code') is-invalid @enderror"
                                >
                                @error('discount_code')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>--}}

                            <div class="cart-total-box mt-4 bg-transparent p-0">

                                {{-- 1) Subtotals: som van alle items --}}
                                <div class="subtotal-item subtotal-box d-flex justify-content-between">
                                    <h4 class="subtotal-title">Subtotals:</h4>
                                    <p class="subtotal-value">{{ Number::currency($sub_total, 'EUR') }}</p>
                                </div>

                                {{-- 2) Taxes (21%) nog altijd 0 in dit voorbeeld --}}
                                <div class="subtotal-item discount-box d-flex justify-content-between">
                                    <h4 class="subtotal-title small text-muted">Taxes (21%):</h4>
                                    <p class="subtotal-value small text-muted">{{ Number::currency($sub_total * 0.21, 'EUR') }}</p>
                                </div>

                                {{-- 3) Shipping Cost --}}
                                <div class="subtotal-item shipping-box d-flex justify-content-between">

                                    @if($free_shipping_threshold > 0 && $sub_total >= $free_shipping_threshold)
                                        <p class="subtotal-value small">
                                            Free Shipping
                                        </p>
                                    @else
                                        <h4 class="subtotal-title small">Shipping Cost:</h4>
                                        <p class="subtotal-value small">
                                            {{ Number::currency($shipping_amount, 'EUR') }}
                                        </p>
                                    @endif

                                </div>

                                <hr />

                                {{-- 4) Total: sub_total + shipping_amount --}}
                                <div class="subtotal-item discount-box d-flex justify-content-between">
                                    <h4 class="subtotal-title">Total:</h4>
                                    <p class="subtotal-value">
                                        {{ Number::currency($grand_total, 'EUR') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .btn-check:checked + .btn-outline-secondary {
            background-color: #00234D;
            color: #fff;
            border-color: #00234D;
        }

        /* form */
        /* Address Selection Styling */
        .address-selection-wrapper {
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 0;
            background: #fff;
        }

        .address-option {
            position: relative;
            border-bottom: 1px solid #e9ecef;
        }

        .address-option:last-child {
            border-bottom: none;
        }

        .address-option input[type="radio"] {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            accent-color: #00234D;
        }

        .address-label {
            display: block;
            padding: 15px 15px 15px 45px;
            margin: 0;
            cursor: pointer;
            transition: background-color 0.2s ease;
            width: 100%;
        }

        .address-label:hover {
            background-color: #f8f9fa;
        }

        .address-option input[type="radio"]:checked + .address-label {
            background-color: #e6f2ff;
            border-left: 4px solid #00234D;
        }

        .address-info strong {
            color: #00234D;
            font-size: 1rem;
        }

        .address-info .text-muted {
            font-size: 0.9rem;
        }

        /* New Address Fields Animation */
        .new-address-fields {
            animation: slideDown 0.3s ease-out;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8f9fa;
            margin-bottom: 20px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                padding-top: 0;
                padding-bottom: 0;
            }
            to {
                opacity: 1;
                max-height: 1000px;
                padding-top: 20px;
                padding-bottom: 20px;
            }
        }


        .form-check-input:checked + .payment-option {
            background-color: #00234D;
            border-color: #00234D;
            color: #fff;
        }

        /* Form Controls */
        .form-control {
            border: 1px solid #e3e3e3;
            border-radius: 6px;
            padding: 12px 15px;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            border-color: #00234D;
            box-shadow: 0 0 0 0.2rem rgba(0, 35, 77, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .payment-methods {
                flex-direction: column;
            }

            .form-check-inline {
                min-width: auto;
            }

            .minicart-btn-area {
                flex-direction: column;
                gap: 10px;
            }

            .checkout-page-btn {
                width: 100%;
                text-align: center;
            }
        }

        input[type="radio"], input[type="checkbox"] {
            width: 1.1em !important;
            height: 1.1em !important;
            appearance: auto !important;
            accent-color: #00234D !important; /* Mag je aanpassen naar je eigen stijl */
            border-radius: 50% !important;   /* Voor radio's */
            background: initial !important;
        }
    </style>

</div>
