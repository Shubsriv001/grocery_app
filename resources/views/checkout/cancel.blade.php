@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center py-5">
            <div class="mb-4">
                <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
            </div>
            <h1 class="mb-4">Payment Cancelled</h1>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <p class="lead mb-4">Your order has been cancelled. No payment has been processed.</p>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('checkout') }}" class="btn btn-primary">
                    <i class="bi bi-credit-card"></i> Try Again
                </a>
                <a href="{{ route('cart') }}" class="btn btn-outline-primary">
                    <i class="bi bi-cart"></i> Return to Cart
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
