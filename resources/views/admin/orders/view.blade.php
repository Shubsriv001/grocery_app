@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>
    
    <div class="row g-4">
        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Order Details</h5>
                        <div>
                            @php
                                $statusClass = [
                                    'pending' => 'bg-warning',
                                    'processing' => 'bg-info',
                                    'shipped' => 'bg-primary',
                                    'delivered' => 'bg-success',
                                    'cancelled' => 'bg-danger'
                                ][$order->status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }} status-badge">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Order Information</h6>
                            <p class="mb-1"><strong>Order ID:</strong> #{{ $order->id }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p class="mb-1">
                                <strong>Payment Method:</strong>
                                @if($order->payment_method == 'cod')
                                    Cash on Delivery
                                @elseif($order->payment_method == 'phonepe')
                                    PhonePe
                                @elseif($order->payment_method == 'stripe')
                                    Stripe
                                @else
                                    {{ $order->payment_method }}
                                @endif
                            </p>
                            @if($order->transaction_id)
                                <p class="mb-1"><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $order->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6>Shipping Address</h6>
                            <p class="mb-1">{{ $order->address }}</p>
                            <p class="mb-1">{{ $order->city }}, {{ $order->state }} {{ $order->pincode }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="80">Image</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($order->items) && count($order->items) > 0)
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            </td>
                                            <td>{{ $item->product->name }}</td>
                                            <td>₹{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No items found</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>₹{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                    <td>₹{{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Shipping:</strong></td>
                                    <td>₹{{ number_format($order->shipping, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>₹{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Update Order Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Status Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Order Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-check-lg text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Order Placed</h6>
                                    <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                            </div>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'success' : 'secondary' }} rounded-circle p-2 me-3">
                                    <i class="bi bi-box text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Processing</h6>
                                    <small class="text-muted">
                                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                            Order is being processed
                                        @else
                                            Waiting for processing
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ in_array($order->status, ['shipped', 'delivered']) ? 'success' : 'secondary' }} rounded-circle p-2 me-3">
                                    <i class="bi bi-truck text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Shipped</h6>
                                    <small class="text-muted">
                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                            Order has been shipped
                                        @else
                                            Waiting for shipment
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $order->status == 'delivered' ? 'success' : 'secondary' }} rounded-circle p-2 me-3">
                                    <i class="bi bi-house-check text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Delivered</h6>
                                    <small class="text-muted">
                                        @if($order->status == 'delivered')
                                            Order has been delivered
                                        @else
                                            Waiting for delivery
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </li>
                        
                        @if($order->status == 'cancelled')
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger rounded-circle p-2 me-3">
                                        <i class="bi bi-x-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Cancelled</h6>
                                        <small class="text-muted">Order has been cancelled</small>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
