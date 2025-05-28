<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'K(l)asse' }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('assets/css/vendor.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link href="{{ asset('assets/css/own-style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/banner.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/breadcrumb.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/cart.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/checkout.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/contact.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/faq.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/newsletter.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/promotional-product.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/wishlist.css') }}" rel="stylesheet">


    <!-- Livewire Alert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">

    @livewireStyles


    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">
    <!-- all css -->
    <style>
        :root {
            --primary-color: #00234D;
            --secondary-color: #F76B6A;

            --btn-primary-border-radius: 0.25rem;
            --btn-primary-color: #fff;
            --btn-primary-background-color: #00234D;
            --btn-primary-border-color: #00234D;
            --btn-primary-hover-color: #fff;
            --btn-primary-background-hover-color: #00234D;
            --btn-primary-border-hover-color: #00234D;
            --btn-primary-font-weight: 500;

            --btn-secondary-border-radius: 0.25rem;
            --btn-secondary-color: #00234D;
            --btn-secondary-background-color: transparent;
            --btn-secondary-border-color: #00234D;
            --btn-secondary-hover-color: #fff;
            --btn-secondary-background-hover-color: #00234D;
            --btn-secondary-border-hover-color: #00234D;
            --btn-secondary-font-weight: 500;

            --heading-color: #000;
            --heading-font-family: 'Poppins', sans-serif;
            --heading-font-weight: 700;

            --title-color: #000;
            --title-font-family: 'Poppins', sans-serif;
            --title-font-weight: 400;

            --body-color: #000;
            --body-background-color: #fff;
            --body-font-family: 'Poppins', sans-serif;
            --body-font-size: 14px;
            --body-font-weight: 400;

            --section-heading-color: #000;
            --section-heading-font-family: 'Poppins', sans-serif;
            --section-heading-font-size: 48px;
            --section-heading-font-weight: 600;

            --section-subheading-color: #000;
            --section-subheading-font-family: 'Poppins', sans-serif;
            --section-subheading-font-size: 16px;
            --section-subheading-font-weight: 400;
        }
    </style>


</head>

<body>

@livewire('partials.navbar')

<div class="body-wrapper">



    <main id="MainContent" class="content-for-layout">

        {{--BREADCRUMB--}}
        {{-- verberg op de homepagina en reset wachtwoord pagina --}}
        @if (!request()->is('/', 'login', 'register') && ! Route::is('password.reset') && ! Route::is('password.request') )

            <livewire:components.breadcrumb/>
        @endif

        {{ $slot }}


    </main>

    @livewireScripts

    <!-- scrollup start -->
    <button class="d-flex justify-content-center align-items-center" id="scrollup">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>
    <!-- scrollup end -->

    <!-- drawer menu start -->
    <div class="offcanvas offcanvas-start d-flex d-lg-none" tabindex="-1" id="drawer-menu">
        <div class="offcanvas-wrapper">
            <div class="offcanvas-header border-btm-black">
                <h5 class="drawer-heading">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
                <nav class="site-navigation">
                    <ul class="main-menu list-unstyled">
                        <li class="menu-list-item nav-item active">
                            <div class="mega-menu-header">
                                <a wire:navigate class="nav-link active" href="/">
                                    Home
                                </a>
                            </div>
                        </li>
                        <li class="menu-list-item nav-item has-megamenu">
                            <div class="mega-menu-header">
                                <a wire:navigate class="nav-link" href="/products">
                                    Products
                                </a>
                            </div>
                        </li>
                        <li class="menu-list-item nav-item">
                            <a wire:navigate class="nav-link" href="/blog">Blog</a>
                        </li>
                        <li class="menu-list-item nav-item has-dropdown">
                            <div class="mega-menu-header">
                                <a wire:navigate class="nav-link active" href="/about-us">
                                    About Us
                                </a>
                            </div>
                        </li>
                        <li class="menu-list-item nav-item">
                            <a wire:navigate class="nav-link" href="/contact">Contact</a>
                        </li>
                    </ul>
                </nav>
                <ul class="utility-menu list-unstyled">
                    <li class="utilty-menu-item">
                        <a class="announcement-text" href="tel:+1-078-2376">
                                <span class="utilty-icon-wrapper">
                                    <svg class="icon icon-phone" xmlns="http://www.w3.org/2000/svg" width="24"
                                         height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                        </path>
                                    </svg>
                                </span>
                            Call: +1 078 2376
                        </a>
                    </li>
                    <li class="utilty-menu-item">
                        <a class="announcement-login announcement-text" href="login.html">
                                <span class="utilty-icon-wrapper">
                                    <svg class="icon icon-user" width="24" height="24" viewBox="0 0 10 11" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5 0C3.07227 0 1.5 1.57227 1.5 3.5C1.5 4.70508 2.11523 5.77539 3.04688 6.40625C1.26367 7.17188 0 8.94141 0 11H1C1 8.78516 2.78516 7 5 7C7.21484 7 9 8.78516 9 11H10C10 8.94141 8.73633 7.17188 6.95312 6.40625C7.88477 5.77539 8.5 4.70508 8.5 3.5C8.5 1.57227 6.92773 0 5 0ZM5 1C6.38672 1 7.5 2.11328 7.5 3.5C7.5 4.88672 6.38672 6 5 6C3.61328 6 2.5 4.88672 2.5 3.5C2.5 2.11328 3.61328 1 5 1Z"
                                            fill="#000"/>
                                    </svg>
                                </span>
                            <span>Login</span>
                        </a>
                    </li>
                    <li class="utilty-menu-item">
                        <a class="header-action-item header-wishlist" href="wishlist.html">
                                <span class="utilty-icon-wrapper">
                                    <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22"
                                         fill="#000" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"
                                            fill="#000"/>
                                    </svg>
                                </span>
                            <span>My wishlist</span>
                        </a>
                    </li>
                    {{--<li class="utilty-menu-item">
                        <button type="button" class="currency-btn btn-reset d-flex align-items-center" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <img class="flag" src="{{ asset('assets/img/flag/eur.jpg') }}" alt="img">
                            <span>EUR</span>
                        </button>
                    </li>--}}
                </ul>
            </div>
        </div>
    </div>
    <!-- drawer menu end -->

    <!-- drawer cart start -->
    <livewire:components.drawer-cart-modal />
    <!-- drawer cart end -->

    <!-- all js -->
    <script src="{{ asset('assets/js/vendor.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Livewire Alert JS -->
    <script src="https://cdn.jsdelivr.net/npm/livewire-alert@1.0.0/dist/livewire-alert.js"></script>

    <!-- Livewire Alert JS -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert', (params) => {
                Swal.fire({
                    icon: params.type,
                    title: params.title,
                    position: params.position || 'center',
                    timer: params.timer || null,
                    toast: params.toast || false,
                    showConfirmButton: !params.toast,
                });
            });
        });
    </script>

    {{-- Zorgt dat de bootstrap dropdowns werken met Livewire --}}
    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.hook('message.processed', () => {
                document
                    .querySelectorAll('[data-bs-toggle="dropdown"]')
                    .forEach(el => bootstrap.Dropdown.getOrCreateInstance(el));
            });
        });
    </script>

</div>
@livewire('partials.footer')
</body>
</html>
