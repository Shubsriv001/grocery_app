<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $productController;

    public function __construct(ProductController $productController)
    {
        $this->productController = $productController;
    }

    public function index()
    {
        $cartItems = session()->get('cart', []);
        $products = $this->productController->getProducts();
        
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $id => $quantity) {
            $product = collect($products)->firstWhere('id', $id);
            if ($product) {
                $items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $quantity
                ];
                $subtotal += $product['price'] * $quantity;
            }
        }

        $tax = $subtotal * 0.18; // 18% GST
        $delivery = 40; // Fixed delivery charge
        $total = $subtotal + $tax + $delivery;

        return view('cart', compact('items', 'subtotal', 'tax', 'delivery', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        session()->put('cart', $cart);
        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'cartCount' => array_sum($cart)
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        
        if (isset($cart[$productId])) {
            $cart[$productId] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'cartCount' => array_sum($cart)
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cartCount' => array_sum($cart)
        ]);
    }

    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        return array_sum($cart);
    }
}
