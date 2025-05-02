@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center py-5">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            </div>
            <h1 class="mb-4">Order Placed Successfully!</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <p class="lead mb-4">Thank you for your order. We'll process it right away!</p>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="bi bi-shop"></i> Continue Shopping
                </a>
                @auth
                    <a href="#" class="btn btn-outline-primary">
                        <i class="bi bi-box"></i> View Orders
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
