<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware is now applied in the routes
    }
    
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $revenue = Order::where('status', '!=', 'cancelled')->sum('total');
        
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalOrders', 
            'pendingOrders', 
            'revenue',
            'recentOrders'
        ));
    }
    
    // Products Management
    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    
    public function createProduct()
    {
        return view('admin.products.create');
    }
    
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'image' => 'required|url',
        ]);
        
        Product::create($request->all());
        
        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully.');
    }
    
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }
    
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'image' => 'required|url',
        ]);
        
        $product = Product::findOrFail($id);
        $product->update($request->all());
        
        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully.');
    }
    
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        return redirect()->route('admin.products')
            ->with('success', 'Product deleted successfully.');
    }
    
    // Orders Management
    public function orders()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.orders.index', compact('orders'));
    }
    
    public function viewOrder($id)
    {
        $order = Order::with('user', 'items')->findOrFail($id);
        return view('admin.orders.view', compact('order'));
    }
    
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        
        return redirect()->route('admin.orders.view', $id)
            ->with('success', 'Order status updated successfully.');
    }
}
