@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 py-lg-5">
    <!-- Mobile Filter Toggle -->
    <div class="d-lg-none mb-3">
        <button class="btn btn-primary w-100" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar">
            <i class="bi bi-funnel"></i> Show Filters
        </button>
    </div>

    <div class="row">
        <!-- Filters Sidebar for Desktop -->
        <div class="col-lg-3 mb-4 d-none d-lg-block">
            <div class="sticky-top" style="top: 1rem;">
                @include('partials.filters')
            </div>
        </div>

        <!-- Filters Offcanvas for Mobile -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="filterSidebar">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                @include('partials.filters')
            </div>
        </div>

        <!-- Products Section -->
        <div class="col-lg-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Search Results Info -->
            @if(request()->has('search') || request()->has('category') || request()->has('min_price') || request()->has('max_price'))
                <div class="mb-4">
                    <h5 class="mb-0">
                        Showing {{ $products->count() }} result(s)
                        @if(request('search'))
                            for "{{ request('search') }}"
                        @endif
                        @if(request('category'))
                            in category: {{ ucfirst(request('category')) }}
                        @endif
                    </h5>
                </div>
            @endif

            @if(count($products) === 0)
                <div class="text-center py-5">
                    <i class="bi bi-emoji-frown" style="font-size: 3rem;"></i>
                    <p class="h5 mt-3">No products found</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Clear Filters</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 product-card">
                                <div class="position-relative">
                                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                    @auth
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 rounded-circle wishlist-btn" 
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    @endauth
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-2">{{ $product->name }}</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <p class="text-primary h5 mb-0">₹{{ number_format($product->price, 2) }}</p>
                                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }} rounded-pill">
                                            {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                        </span>
                                    </div>
                                    <p class="card-text small text-muted mb-3">{{ Str::limit($product->description, 60) }}</p>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary add-to-cart"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}">
                                            <i class="bi bi-cart-plus"></i> Add to Cart
                                        </button>
                                        <button class="btn btn-outline-primary quick-view" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#quickViewModal"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="₹{{ number_format($product->price, 2) }}"
                                            data-product-description="{{ $product->description }}"
                                            data-product-image="{{ $product->image }}">
                                            <i class="bi bi-eye"></i> Quick View
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <img src="" id="quickViewImage" class="img-fluid rounded" alt="Product">
                    </div>
                    <div class="col-md-6">
                        <h3 id="quickViewName" class="mb-2"></h3>
                        <p class="text-primary fs-4 mb-3" id="quickViewPrice"></p>
                        <p class="mb-4" id="quickViewDescription"></p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary add-to-cart"
                                id="quickViewAddToCart"
                                data-product-id=""
                                data-product-name="">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                            @auth
                                <button class="btn btn-outline-primary toggle-wishlist"
                                    id="quickViewToggleWishlist"
                                    data-product-id=""
                                    data-product-name="">
                                    <i class="bi bi-heart"></i> Add to Wishlist
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
