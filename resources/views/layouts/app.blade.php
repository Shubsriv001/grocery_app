<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sant Lal's Store</title>
    @php
        $cartCount = array_sum(session('cart', []));
    @endphp
    <meta name="description" content="Sant Lal's Store - Your one-stop online grocery store for fresh fruits, vegetables, and daily essentials.">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/logo.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="user-auth" content="true">
    @endauth
    <style>
        /* Smooth scrolling and better font rendering */
        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Product Card Styles */
        .card.hover-shadow {
            transition: all 0.3s ease;
            border: none;
        }

        .card.hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .card .card-img-top {
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .btn-outline-primary:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .badge.rounded-pill {
            padding: 0.5em 1em;
            font-weight: 500;
        }

        .text-primary {
            color: #4f46e5 !important;
        }

        /* Prevent FOUC */
        html.loading * {
            transition: none !important;
        }

        /* Card Styles */
        .card {
            transition: transform 0.2s;
            margin-bottom: 1rem;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-overlay .btn {
            transform: translateY(20px);
            transition: transform 0.3s ease;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-card:hover .product-overlay .btn {
            transform: translateY(0);
        }

        .product-overlay .btn:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Navigation Styles */
        .navbar-brand img {
            transition: transform 0.3s;
            width: 32px; /* Smaller on mobile */
        }
        @media (min-width: 768px) {
            .navbar-brand img {
                width: 40px;
            }
        }
        .navbar-brand:hover img {
            transform: scale(1.1);
        }
        .nav-link {
            position: relative;
            padding: 0.5rem 1rem;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: currentColor;
            transition: all 0.3s ease;
        }
        .nav-link:hover::after {
            width: 80%;
            left: 10%;
        }
        .nav-link.active::after {
            width: 80%;
            left: 10%;
        }
        #categoriesNav .nav-link {
            color: #444;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem;
        }
        @media (min-width: 768px) {
            #categoriesNav .nav-link {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }
        }
        #categoriesNav .nav-link:hover {
            color: var(--bs-primary);
        }
        #categoriesNav .nav-link i {
            margin-right: 5px;
        }
        .search-input:focus {
            box-shadow: none;
            border-color: var(--bs-primary);
        }

        /* Mobile Search */
        @media (max-width: 991px) {
            .search-form {
                width: 100% !important;
                margin: 1rem 0;
            }
        }

        /* Toast and Notifications */
        .toast-container {
            position: fixed;
            bottom: 20px; /* Changed from top to bottom for mobile */
            right: 20px;
            z-index: 1050;
            max-width: calc(100% - 40px);
        }
        .wishlist-preview {
            width: 100%;
            max-width: 320px;
            max-height: 400px;
            overflow-y: auto;
        }
        .wishlist-preview::-webkit-scrollbar {
            width: 6px;
        }
        .wishlist-preview::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,.2);
            border-radius: 3px;
        }
        .wishlist-item-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .wishlist-item-name {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Responsive Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        /* Mobile Footer */
        @media (max-width: 767px) {
            .footer {
                text-align: center;
            }
            .footer .col-lg-4 {
                margin-bottom: 2rem;
            }
        }

        /* Theme toggle styles */
        .nav-item .theme-toggle {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: rgba(0, 0, 0, 0.65);
            text-decoration: none;
            transition: color 0.15s ease-in-out;
        }

        [data-bs-theme="dark"] .nav-item .theme-toggle {
            color: rgba(255, 255, 255, 0.65);
        }

        .nav-item .theme-toggle:hover,
        .nav-item .theme-toggle:focus {
            color: rgba(0, 0, 0, 0.8);
            background-color: transparent;
        }

        [data-bs-theme="dark"] .nav-item .theme-toggle:hover,
        [data-bs-theme="dark"] .nav-item .theme-toggle:focus {
            color: rgba(255, 255, 255, 0.8);
        }

        .theme-toggle .bi {
            font-size: 1.2rem;
            vertical-align: middle;
            margin-right: 0.25rem;
        }

        /* Dark mode styles */
        [data-bs-theme="dark"] {
            --background-light: #0f172a;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --border-color: #1e293b;
            --bs-body-bg: #0f172a;
            --bs-body-color: #e2e8f0;
            --bs-card-bg: #1e293b;
            --bs-card-border-color: #334155;
            --bs-link-color: #60a5fa;
            --bs-link-hover-color: #93c5fd;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.2), 0 2px 4px -2px rgb(0 0 0 / 0.2);
        }

        [data-bs-theme="dark"] .navbar {
            background: linear-gradient(to right, #0f172a, #1e293b);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .categories-nav {
            background: linear-gradient(to right, var(--primary-color), var(--primary-hover));
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="dark"] .navbar .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        [data-bs-theme="dark"] .navbar .nav-link:hover,
        [data-bs-theme="dark"] .navbar .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="dark"] .navbar .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.2);
        }

        [data-bs-theme="dark"] .navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        [data-bs-theme="dark"] .categories-nav {
            background: linear-gradient(to right, #0f172a, #1e293b);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        [data-bs-theme="dark"] .card:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.3), 0 4px 6px -4px rgb(0 0 0 / 0.3);
        }

        [data-bs-theme="dark"] .navbar {
            background-color: var(--bs-navbar-bg);
            color: var(--bs-navbar-color);
        }

        [data-bs-theme="dark"] .card {
            background-color: var(--bs-card-bg);
            border-color: var(--bs-card-border-color);
        }

        [data-bs-theme="dark"] .text-muted {
            color: #adb5bd !important;
        }
        
        /* Footer styles for both themes */
        .footer {
            color: var(--bs-dark);
        }
        
        .footer .text-muted {
            color: #6c757d !important;
        }
        
        .footer h5, .footer h6 {
            font-weight: 600;
        }
        
        .footer .social-links a {
            color: #212529;
            opacity: 0.8;
            transition: opacity 0.2s, color 0.2s;
        }
        
        .footer .social-links a:hover {
            opacity: 1;
            color: var(--bs-primary);
        }
        
        /* Footer dark mode styles */
        [data-bs-theme="dark"] .footer {
            color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .footer h5, [data-bs-theme="dark"] .footer h6 {
            color: #f8f9fa !important;
        }
        
        [data-bs-theme="dark"] .footer .text-muted {
            color: #adb5bd !important;
        }
        
        [data-bs-theme="dark"] .footer .social-links a {
            color: #e9ecef;
            opacity: 0.8;
        }
        
        [data-bs-theme="dark"] .footer .social-links a:hover {
            opacity: 1;
            color: var(--bs-primary);
        }
    </style>
</head>
<body class="{{ !in_array(Route::currentRouteName(), ['login', 'register']) ? 'pt-5' : '' }}" style="{{ !in_array(Route::currentRouteName(), ['login', 'register']) ? 'padding-top: 120px !important;' : '' }}">
    <!-- Main Navigation -->
    @if(!in_array(Route::currentRouteName(), ['login', 'register']))
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top py-3" style="z-index: 1030;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand logo-container" href="{{ route('home') }}">
                <svg class="logo-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4H5.62L6.72 16.43C6.78 17.29 7.51 17.95 8.37 17.95H18.35C19.19 17.95 19.92 17.31 20 16.47L20.83 8.02C20.92 7.08 20.18 6.27 19.23 6.27H6.82" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 11H17" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 2H3.31C3.95 2 4.48 2.44 4.6 3.06L5.17 5.94" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.5 21.5C10.3284 21.5 11 20.8284 11 20C11 19.1716 10.3284 18.5 9.5 18.5C8.67157 18.5 8 19.1716 8 20C8 20.8284 8.67157 21.5 9.5 21.5Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17.5 21.5C18.3284 21.5 19 20.8284 19 20C19 19.1716 18.3284 18.5 17.5 18.5C16.6716 18.5 16 19.1716 16 20C16 20.8284 16.6716 21.5 17.5 21.5Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="logo-text">Sant Lal's Store</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Box -->
                <form class="d-flex mx-auto search-form" style="width: 40%;" action="{{ route('home') }}" method="GET">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" placeholder="Search grocery items..." value="{{ request('search') }}" aria-label="Search">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Right Navigation Items -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="nav-link theme-toggle" id="theme-toggle" type="button">
                            <i class="bi bi-sun-fill theme-icon-light"></i>
                            <i class="bi bi-moon-fill theme-icon-dark d-none"></i>
                            <span class="ms-1 d-none d-sm-inline">Theme</span>
                        </button>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-person-circle"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="wishlistDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="bi bi-heart"></i>
                                @php
                                    $wishlistCount = Auth::check() ? App\Models\Wishlist::where('user_id', Auth::id())->count() : 0;
                                @endphp
                                @if($wishlistCount > 0)
                                    <span class="badge bg-danger rounded-pill" style="position: relative; top: -8px; left: -5px;">
                                        {{ $wishlistCount }}
                                    </span>
                                @endif
                                Wishlist
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm" style="margin-top: 1rem !important; min-width: 300px;">
                                <div class="p-3">
                                    <h6 class="mb-3">My Wishlist</h6>
                                    @php
                                        $wishlistItems = Auth::check() ? App\Models\Wishlist::where('user_id', Auth::id())
                                            ->take(3)
                                            ->get()
                                            ->map(function($item) {
                                                return App\Models\Product::find($item->product_id);
                                            })
                                            ->filter() : [];
                                    @endphp

                                    @forelse($wishlistItems as $item)
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div class="ms-3 flex-grow-1">
                                                <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{ $item['name'] }}</h6>
                                                <small class="text-muted">₹{{ number_format($item['price'], 2) }}</small>
                                            </div>
                                            <form action="{{ route('cart.add') }}" method="POST" class="ms-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Add to Cart">
                                                    <i class="bi bi-cart-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">Your wishlist is empty</p>
                                    @endforelse

                                    @if($wishlistCount > 3)
                                        <div class="text-muted small mb-2">Showing 3 of {{ $wishlistCount }} items</div>
                                    @endif

                                    <a href="{{ route('wishlist') }}" class="btn btn-outline-primary btn-sm d-block mt-3">
                                        View All Wishlist Items
                                    </a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('wishlist') }}"><i class="bi bi-heart"></i> My Wishlist</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history"></i> Order History</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart') }}">
                            <i class="bi bi-cart3"></i>
                            @php
                                $cartCount = array_sum(session('cart', []));
                            @endphp
                            <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="{{ $cartCount > 0 ? '' : 'display: none;' }}">{{ $cartCount }}</span>
                            <span class="ms-1">Cart</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endif

    <!-- Categories Navigation -->
    @if(!in_array(Route::currentRouteName(), ['login', 'register']))
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top py-3" style="top: 56px; margin-top: 10px;">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#categoriesNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="categoriesNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('category') || request('category') == 'all' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'all', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">All Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') == 'Fruits' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'Fruits', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">
                           <i class="bi bi-apple"></i> Fruits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') == 'Vegetables' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'Vegetables', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">
                           <i class="bi bi-flower1"></i> Vegetables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') == 'Bakery' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'Bakery', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">
                           <i class="bi bi-egg-fried"></i> Bakery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') == 'Dairy' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'Dairy', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">
                           <i class="bi bi-cup-hot"></i> Dairy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') == 'Meat' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'Meat', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">
                           <i class="bi bi-egg-fried"></i> Meat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') == 'Seafood' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'Seafood', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">
                           <i class="bi bi-water"></i> Seafood</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') == 'Pantry' ? 'active' : '' }}" 
                           href="{{ route('home', ['category' => 'Pantry', 'search' => request('search'), 'sort' => request('sort'), 'direction' => request('direction')]) }}">
                           <i class="bi bi-basket"></i> Pantry</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endif

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1070;"></div>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    @if(!in_array(Route::currentRouteName(), ['login', 'register']))
    <footer class="footer mt-auto py-5" style="background-color: var(--bs-body-bg); border-top: 1px solid var(--bs-border-color);">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="mb-4" style="color: var(--bs-heading-color, #212529);">Sant Lal's Store</h5>
                    <p class="text-muted mb-4">Your one-stop destination for fresh groceries and everyday essentials. Quality products delivered with care.</p>
                    <div class="social-links d-flex gap-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h6 class="mb-4" style="color: var(--bs-heading-color, #212529);">Quick Links</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-muted">Home</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Shop</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Contact</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h6 class="mb-4" style="color: var(--bs-heading-color, #212529);">Categories</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="#" class="text-muted">Fruits & Vegetables</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Dairy & Eggs</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Meat & Seafood</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Bakery</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Beverages</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="mb-4" style="color: var(--bs-heading-color, #212529);">Contact Us</h6>
                    <form id="contactForm" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control border-secondary" placeholder="Your Email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control border-secondary" rows="3" placeholder="Your Message" required></textarea>
                            <div class="invalid-feedback" id="messageError"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="sendMessageBtn">
                            <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                            Send Message
                        </button>
                    </form>
                    <div class="contact-info text-muted">
                        <p class="mb-2"><i class="bi bi-geo-alt me-2"></i>Flat no 302, Pocket - D, Sector B2, Narela, Delhi - 100040</p>
                        <p class="mb-2"><i class="bi bi-telephone me-2"></i><a href="tel:+919934400220" class="footer-link">+91 99344 00220</a></p>
                        <p class="mb-0"><i class="bi bi-envelope me-2"></i><a href="mailto:Aryanaryan46740@gmail.com" class="footer-link">Aryanaryan46740@gmail.com</a></p>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-top mt-4 pt-4" style="border-color: var(--bs-border-color) !important;">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="mb-0 text-muted">&copy; {{ date('Y') }} Sant Lal's Store. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="#" class="text-muted">Terms & Conditions</a></li>
                            <li class="list-inline-item"><span class="text-muted mx-2">|</span></li>
                            <li class="list-inline-item"><a href="#" class="text-muted">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @endif

    <!-- Success Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body"></div>
        </div>

        <!-- Error Toast -->
        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Contact Form Success Modal -->
    <div class="modal fade" id="contactSuccessModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Message Sent!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Thank you for contacting us. We'll get back to you soon!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('wishlist') }}" class="btn btn-primary">View Full Wishlist</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/wishlist.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/contact.js') }}"></script>
    <script src="{{ asset('js/quick-view.js') }}"></script>

    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <!-- Cart Toast -->
        <div class="toast" id="cartToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Cart Updated</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="cartToastBody"></div>
        </div>

        <!-- Wishlist Toast -->
        <div class="toast" id="wishlistToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Wishlist Updated</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="wishlistToastBody"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Prevent FOUC
        document.documentElement.classList.add('loading');

        // Custom JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Remove loading class after DOM is ready
            document.documentElement.classList.remove('loading');
            // Theme toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;
            const lightIcon = themeToggle.querySelector('.theme-icon-light');
            const darkIcon = themeToggle.querySelector('.theme-icon-dark');

            // Load saved theme preference
            const savedTheme = localStorage.getItem('theme') || 'dark';
            html.setAttribute('data-bs-theme', savedTheme);
            updateThemeIcons(savedTheme);

            // Update meta theme-color based on theme
            const metaThemeColor = document.querySelector('meta[name="theme-color"]');
            updateMetaThemeColor(savedTheme);

            themeToggle.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcons(newTheme);
                updateMetaThemeColor(newTheme);

                // Send theme preference to server
                fetch('/update-theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ theme: newTheme })
                });
            });

            function updateThemeIcons(theme) {
                if (theme === 'dark') {
                    lightIcon.classList.add('d-none');
                    darkIcon.classList.remove('d-none');
                } else {
                    lightIcon.classList.remove('d-none');
                    darkIcon.classList.add('d-none');
                }
            }

            function updateMetaThemeColor(theme) {
                metaThemeColor.content = theme === 'dark' ? '#0f172a' : '#4f46e5';
            }
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });

            return toast;
        }

        // Update wishlist count in navbar
        function updateWishlistCount(count) {
            const badge = document.querySelector('#wishlistDropdown .badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            }
        }

        // Initialize all tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
        });
    </script>
    @stack('scripts')
</body>
</html>
