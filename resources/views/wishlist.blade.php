@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5" style="margin-top: 6rem !important;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">My Wishlist</h2>
        <div class="d-flex gap-2">
            <button id="addAllToCart" class="btn btn-primary">
                <i class="bi bi-cart-plus"></i> Add All to Cart
            </button>
            <button id="removeSelected" class="btn btn-danger d-none">
                <i class="bi bi-trash"></i> Remove Selected
            </button>
        </div>
    </div>

    @if($items->isEmpty())
        <div class="alert alert-info">
            Your wishlist is empty. <a href="{{ route('home') }}">Continue shopping</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Filters</h5>
                        <form id="filterForm" method="GET" action="{{ route('wishlist') }}">
                            <div class="mb-3">
                                <label class="form-label">Price Range</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="number" name="min_price" class="form-control form-control-sm" 
                                        placeholder="Min" value="{{ $current_min ?? $min_price }}">
                                    <span>-</span>
                                    <input type="number" name="max_price" class="form-control form-control-sm" 
                                        placeholder="Max" value="{{ $current_max ?? $max_price }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sort by Price</label>
                                <select name="sort" class="form-select form-select-sm">
                                    <option value="">Default</option>
                                    <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Low to High</option>
                                    <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>High to Low</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Apply Filters</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($items as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="{{ $item->image }}" class="card-img-top" alt="{{ $item->name }}" style="height: 200px; object-fit: cover;">
                                <div class="form-check position-absolute top-0 start-0 m-2">
                                    <input class="form-check-input item-checkbox" type="checkbox" value="{{ $item->id }}">
                                </div>
                                <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-from-wishlist" 
                                        data-product-id="{{ $item->id }}" 
                                        data-product-name="{{ $item->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text text-primary mb-2">₹{{ number_format($item->price, 2) }}</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm add-to-cart flex-grow-1" 
                                            data-product-id="{{ $item->id }}" 
                                            data-product-name="{{ $item->name }}">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle individual item removal
    document.querySelectorAll('.remove-from-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            removeFromWishlist(productId, productName);
        });
    });

    function removeFromWishlist(productId, productName) {
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'removed') {
                const card = document.querySelector(`[data-product-id="${productId}"]`).closest('.col');
                card.remove();
                
                // Show success message
                const toast = new bootstrap.Toast(document.getElementById('wishlistToast'));
                document.querySelector('#wishlistToastBody').textContent = `${productName} removed from wishlist!`;
                toast.show();
                
                // If no items left, show empty message
                if (document.querySelectorAll('.col').length === 0) {
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const toast = new bootstrap.Toast(document.getElementById('wishlistToast'));
            document.querySelector('#wishlistToastBody').textContent = 'Error removing item. Please try again.';
            toast.show();
        });
    }
});
</script>
@endpush
