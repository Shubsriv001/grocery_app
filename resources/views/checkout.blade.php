@extends('layouts.app')

@section('content')
<div class="container py-3 py-lg-4">
    <h1 class="h2 mb-4">Checkout</h1>

    <!-- Order Summary for Mobile -->
    <div class="d-block d-md-none mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Order Summary</h5>
                @if(isset($items) && count($items) > 0)
                    <div class="mb-3">
                        @foreach($items as $item)
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="ms-2 flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="small">{{ $item['name'] }}</span>
                                        <span class="small text-muted">x{{ $item['quantity'] }}</span>
                                    </div>
                                    <span class="small text-muted">₹{{ number_format($item['price'], 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>₹{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>₹{{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                        <strong>Total:</strong>
                        <strong>₹{{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" class="needs-validation" novalidate>
                @csrf
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Contact Information</h5>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required pattern="[A-Za-z ]{3,}" minlength="3">
                                <div class="invalid-feedback">
                                    Please enter a valid name (minimum 3 characters)
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                <div class="invalid-feedback">
                                    Please enter a valid email address
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" required pattern="[0-9]{10}" minlength="10" maxlength="10">
                            <div class="invalid-feedback">
                                Please enter a valid 10-digit phone number
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Shipping Information</h5>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" required minlength="5">
                            <div class="invalid-feedback">
                                Please enter your complete address
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required pattern="[A-Za-z ]{2,}">
                                <div class="invalid-feedback">
                                    Please enter a valid city name
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" required pattern="[A-Za-z ]{2,}">
                                <div class="invalid-feedback">
                                    Please enter a valid state name
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3">
                                <label for="pincode" class="form-label">PIN Code</label>
                                <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode') }}" required pattern="[0-9]{6}" maxlength="6">
                                <div class="invalid-feedback">
                                    Please enter a valid 6-digit PIN code
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Payment Method</h5>
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" required checked>
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery (COD)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="phonepe" value="phonepe" required>
                                <label class="form-check-label" for="phonepe">
                                    PhonePe
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" required>
                                <label class="form-check-label" for="stripe">
                                    Credit/Debit Card (Stripe)
                                </label>
                            </div>
                            <div class="invalid-feedback">
                                Please select a payment method
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" id="place-order-btn">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Order Summary for Desktop -->
        <div class="col-md-4 d-none d-md-block">
            <div class="card shadow-sm sticky-top" style="top: 1rem;">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    @if(isset($items) && count($items) > 0)
                        <div class="mb-4">
                            @foreach($items as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="ms-3 flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-medium">{{ $item['name'] }}</div>
                                                <div class="small text-muted">Qty: {{ $item['quantity'] }}</div>
                                            </div>
                                            <span class="ms-2">₹{{ number_format($item['price'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>₹{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>₹{{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                            <strong>Total:</strong>
                            <strong>₹{{ number_format($total, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<div class="toast align-items-center text-white bg-danger border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            {{ session('error') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('checkout-form');
    const submitButton = document.getElementById('place-order-btn');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
        }
        form.classList.add('was-validated');
    });

    // Phone number validation
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) value = value.slice(0, 10);
        e.target.value = value;
    });

    // PIN code validation
    const pincodeInput = document.getElementById('pincode');
    pincodeInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 6) value = value.slice(0, 6);
        e.target.value = value;
    });

    // Show toast if exists
    const toastEl = document.querySelector('.toast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});
</script>
@endpush
