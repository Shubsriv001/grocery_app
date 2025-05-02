@extends('layouts.app')

@section('content')
<div class="container py-3 py-lg-4">
    <h1 class="h2 mb-4">Shopping Cart</h1>
    
    <!-- Order Summary for Mobile -->
    <div class="d-block d-md-none mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Order Summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₹{{ number_format($subtotal ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax:</span>
                    <span>₹{{ number_format($tax ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery Charge:</span>
                    <span>₹{{ number_format($delivery ?? 40, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong>₹{{ number_format($total ?? 0, 2) }}</strong>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(isset($items) && count($items) > 0)
                        @foreach($items as $item)
                        <div class="card mb-3 shadow-sm cart-item">
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Product Image -->
                                    <div class="col-4 col-sm-3">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="width: 100%; height: 100px; object-fit: cover;">
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="col-8 col-sm-9">
                                        <div class="d-flex flex-column h-100">
                                            <h5 class="mb-2">{{ $item['name'] }}</h5>
                                            <p class="text-muted mb-2">₹{{ number_format($item['price'], 2) }}</p>
                                            
                                            <div class="mt-auto">
                                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                                    <!-- Quantity Update -->
                                                    <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                        <div class="input-group input-group-sm" style="width: 120px;">
                                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control">
                                                            <button type="submit" class="btn btn-outline-primary">Update</button>
                                                        </div>
                                                    </form>
                                                    
                                                    <!-- Remove Button -->
                                                    <form action="{{ route('cart.remove') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                
                                                <p class="text-muted mt-2 mb-0">
                                                    <small>Subtotal: ₹{{ number_format($item['price'] * $item['quantity'], 2) }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                            <p class="h5 mt-3">Your cart is empty</p>
                            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Order Summary for Desktop -->
        <div class="col-md-4 d-none d-md-block">
            <div class="card shadow-sm sticky-top" style="top: 120px; max-height: calc(100vh - 150px); overflow-y: auto;">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>₹{{ number_format($tax ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Charge:</span>
                        <span>₹{{ number_format($delivery ?? 40, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>₹{{ number_format($total ?? 0, 2) }}</strong>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
