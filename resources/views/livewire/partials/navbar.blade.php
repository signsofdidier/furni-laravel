<div>
    <!-- announcement bar start -->
    <div class="announcement-bar bg-1 py-1 py-lg-2">
        <div class="container-fluid px-0"></div>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-3 d-lg-block d-none">
                    <div class="announcement-call-wrapper">
                        <div class="announcement-call">
                            <a class="announcement-text text-white" href="tel:+1-078-2376">Call: +1 078 2376</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="announcement-text-wrapper d-flex align-items-center justify-content-center">
                        @if($free_shipping_enabled && $free_shipping_threshold > 0)
                            <p class="announcement-text text-white">Free Shipping on all orders
                                over {{ Number::currency($free_shipping_threshold, 'EUR') }}.</p>
                        @else
                            <p class="announcement-text text-white">Free Shipping on all orders.</p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 d-lg-block d-none">
                    <div class="announcement-meta-wrapper d-flex align-items-center justify-content-end">
                        <div class="announcement-meta">

                            {{-- Login en register knop voor gasten --}}
                            @guest
                                {{-- REGISTER --}}
                                <div class="d-flex gap-3">
                                    <a class="d-flex align-items-center text-white text-decoration-none" href="{{ url('/register') }}">
                                        <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 512" fill="currentColor">
                                            <path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312v-64h-64c-13.3 0-24-10.7-24-24s10.7-24 24-24h64v-64c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24h-64v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                                        </svg>
                                        <span class="fw-semibold">Register</span>
                                    </a>


                                    {{--LOGIN--}}
                                    <a class="d-flex align-items-center text-white text-decoration-none"
                                       href="{{ url('/login') }}">
                                        {{-- User-icon --}}
                                        <svg class="me-1" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                            <path fill-rule="evenodd" d="M8 9a5 5 0 0 0-5 5v1h10v-1a5 5 0 0 0-5-5z"/>
                                        </svg>
                                        <span class="fw-semibold">Login</span>
                                    </a>
                                </div>

                                {{-- Dropdown voor ingelogde gebruikers --}}
                            @else
                                <div class="dropdown">
                                    <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                                       href="#"
                                       id="navbarUserDropdown"
                                       data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                        {{-- Optioneel een avatar, anders deze icon --}}
                                        <img
                                            src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : asset('assets/img/default-avatar.png') }}"
                                            alt="{{ Auth::user()->name }} Profile Photo"
                                            class="rounded-circle object-cover border-2 border-white me-2"
                                            style="width:32px ; height: 32px;">
                                        <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2"
                                        aria-labelledby="navbarUserDropdown" style="min-width: 12rem;">
                                        <li class="px-3 py-2">
                                            <div class="d-flex align-items-center">
                                                <img
                                                    src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : asset('assets/img/checkout/user.jpg') }}"
                                                    alt="{{ Auth::user()->name }} Profile Photo"
                                                    class="rounded-circle object-cover me-2"
                                                    style="width:50px ; height: 50px;">
                                                <div>
                                                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <a class="dropdown-item d-flex align-items-center py-2"
                                               href="{{ url('/profile') }}">
                                                {{-- Profile-icon --}}
                                                <svg class="me-2" width="18" height="18" fill="currentColor"
                                                     viewBox="0 0 16 16">
                                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3z"/>
                                                    <path fill-rule="evenodd" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                                Profile Page
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center py-2"
                                               href="{{ url('/my-orders') }}">
                                                {{-- Orders-icon --}}
                                                <svg class="me-2" width="18" height="18" fill="currentColor"
                                                     viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 1.5A.5.5 0 0 1 .5 1h15a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-11zM1 2v10h14V2H1z"/>
                                                    <path d="M3 4h10v2H3V4zm0 3h10v2H3V7z"/>
                                                </svg>
                                                My Orders
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <a class="dropdown-item d-flex align-items-center text-danger py-2"
                                               href="{{ url('/logout') }}">
                                                {{-- Logout-icon --}}
                                                <svg class="me-2 text-danger" width="18" height="18" fill="currentColor"
                                                     viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                          d="M6 3.5a.5.5 0 0 1 .5-.5h5.793l-1.147-1.146a.5.5 0 1 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L12.293 4H6.5a.5.5 0 0 1-.5-.5z"/>
                                                    <path fill-rule="evenodd"
                                                          d="M13 8a.5.5 0 0 1-.5.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5H12.5A.5.5 0 0 1 13 8z"/>
                                                </svg>
                                                Logout
                                            </a>
                                            <form id="logout-form" action="/logout" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- announcement bar end -->

    <!-- header start -->
    <header class="sticky-header border-btm-black header-1">
        <div class="header-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-4">
                        <div class="header-logo">
                            <a href="/" class="logo-main">
                                <img src="{{ asset('assets/img/logo.png') }}" loading="lazy" alt="bisum">
                                {{--<h2 class="text-bold">K(L)ASSE</h2>--}}
                            </a>
                        </div>
                    </div>

                    {{-- NAVBAR LINKS --}}
                    <div class="col-lg-6 d-lg-block d-none">
                        <nav class="site-navigation">
                            <ul class="main-menu list-unstyled justify-content-center">
                                <li class="menu-list-item nav-item {{ request()->is('/') ? 'active' : '' }}">
                                    <a wire:navigate class="nav-link" href="{{ url('/') }}">
                                        Home
                                    </a>
                                </li>
                                <li class="menu-list-item nav-item {{ request()->is('products') ? 'active' : '' }}">
                                    <a wire:navigate class="nav-link" href="{{ url('/products') }}">
                                        Products
                                    </a>
                                </li>
                                <li class="menu-list-item nav-item {{ request()->is('blog') ? 'active' : '' }}">
                                    <a wire:navigate class="nav-link" href="{{ url('/blog') }}">
                                        Blog
                                    </a>
                                </li>
                                {{--<li class="menu-list-item nav-item {{ request()->is('about-us') ? 'active' : '' }}">
                                    <a wire:navigate class="nav-link" href="{{ url('/about-us') }}">
                                        About Us
                                    </a>
                                </li>--}}
                                {{--<li wire:navigate class="menu-list-item nav-item {{ request()->is('contact') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                                </li>--}}
                            </ul>
                        </nav>
                    </div>


                    <div class="col-lg-3 col-md-8 col-8">
                        <div class="header-action d-flex align-items-center justify-content-end">
                            <a class="header-action-item header-search" href="javascript:void(0)">
                                <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z"
                                        fill="black"/>
                                </svg>
                            </a>


                            {{-- WISHLIST --}}
                            <a class="header-action-item header-wishlist ms-4 d-none d-lg-block"
                               href="{{ url('/wishlist') }}">
                                <livewire:wishlist-count-icon />
                            </a>

                            {{-- CART --}}

                            <flux:modal.trigger name="cart"
                                                class="relative flex items-center ms-4 gap-3 cursor-pointer">
                                <!-- Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="26" fill="none"
                                     viewBox="0 0 24 26">
                                    <path
                                        d="M12 0C9.25 0 7 2.25 7 5v1H2.06L2 6.94 1 24.94.94 26h22.12L23 24.94l-1-18L21.94 6H17V5c0-2.75-2.25-5-5-5ZM12 2c1.66 0 3 1.34 3 3v1H9V5c0-1.66 1.34-3 3-3ZM3.94 8H7v3h2V8h6v3h2V8h3.06l.88 16H3.06L3.94 8Z"
                                        fill="currentColor"/>
                                </svg>

                                @if($total_count > 0)
                                    <span
                                        class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center">
                                                {{ $total_count }}
                                            </span>
                                @endif
                            </flux:modal.trigger>

                            {{-- hamburger icon drawer --}}
                            <a class="header-action-item header-hamburger ms-4 d-lg-none" href="#drawer-menu"
                               data-bs-toggle="offcanvas">
                                <svg class="icon icon-hamburger" xmlns="http://www.w3.org/2000/svg" width="24"
                                     height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="3" y1="12" x2="21" y2="12"></line>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <line x1="3" y1="18" x2="21" y2="18"></line>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-wrapper">
                <div class="container">
                    <form action="#" class="search-form d-flex align-items-center">
                        <button type="submit" class="search-submit bg-transparent pl-0 text-start">
                            <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z"
                                    fill="black"/>
                            </svg>
                        </button>
                        <div class="search-input mr-4">
                            <input type="text" placeholder="Search your products..." autocomplete="off">
                        </div>
                        <div class="search-close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="icon icon-close">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <!-- header end -->
</div>
