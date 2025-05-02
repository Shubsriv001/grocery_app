@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Orders</h1>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">
                    All Orders
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-outline-primary {{ request('status') == 'pending' ? 'active' : '' }}">
                    Pending
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn btn-outline-primary {{ request('status') == 'processing' ? 'active' : '' }}">
                    Processing
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="btn btn-outline-primary {{ request('status') == 'shipped' ? 'active' : '' }}">
                    Shipped
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="btn btn-outline-primary {{ request('status') == 'delivered' ? 'active' : '' }}">
                    Delivered
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-outline-primary {{ request('status') == 'cancelled' ? 'active' : '' }}">
                    Cancelled
                </a>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <div class="fw-medium">{{ $order->user->name }}</div>
                                    <div class="small text-muted">{{ $order->user->email }}</div>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($order->payment_method == 'cod')
                                        <span class="badge bg-secondary">Cash on Delivery</span>
                                    @elseif($order->payment_method == 'phonepe')
                                        <span class="badge bg-info">PhonePe</span>
                                    @elseif($order->payment_method == 'stripe')
                                        <span class="badge bg-primary">Stripe</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                                    @endif
                                </td>
                                <td>₹{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <div class="dropdown">
                                        @php
                                            $statusClass = [
                                                'pending' => 'bg-warning',
                                                'processing' => 'bg-info',
                                                'shipped' => 'bg-primary',
                                                'delivered' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ][$order->status] ?? 'bg-secondary';
                                        @endphp
                                        <button class="btn btn-sm {{ $statusClass }} dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            {{ ucfirst($order->status) }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                                <li>
                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $status }}">
                                                        <button type="submit" class="dropdown-item {{ $order->status === $status ? 'active' : '' }}">
                                                            {{ ucfirst($status) }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                        No orders found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
