<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!session()->has('cart') || empty(session('cart'))) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $cart = session('cart');
        $items = [];
        $subtotal = 0;

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product) {
                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image
                ];
                $subtotal += $product->price * $quantity;
            }
        }

        if (empty($items)) {
            return redirect()->route('cart')->with('error', 'Invalid items in cart');
        }

        $shipping = 40; 
        $tax = $subtotal * 0.18; // 18% GST
        $total = $subtotal + $shipping + $tax;

        // Store checkout values in session
        session(['checkout' => [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total
        ]]);

        return view('checkout', compact('items', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function processPayment(Request $request)
    {
        if (!session()->has('checkout')) {
            return redirect()->route('cart')->with('error', 'Please review your cart before checkout');
        }

        $request->validate([
            'payment_method' => 'required|in:cod,phonepe,stripe',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:15',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|size:6'
        ]);

        $checkout = session('checkout');
        
        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'subtotal' => $checkout['subtotal'],
            'tax' => $checkout['tax'],
            'shipping' => $checkout['shipping'],
            'total' => $checkout['total']
        ]);

        // Save order items
        foreach ($checkout['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        switch ($request->payment_method) {
            case 'cod':
                // Clear cart and checkout session
                session()->forget(['cart', 'checkout']);
                return redirect()->route('checkout.success')->with('success', 'Order placed successfully! You will pay ₹' . number_format($checkout['total'], 2) . ' on delivery.');

            case 'phonepe':
                // Implement PhonePe integration here
                return redirect()->route('checkout.cancel')->with('error', 'PhonePe payment is currently unavailable');

            case 'stripe':
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    
                    $session = StripeSession::create([
                        'payment_method_types' => ['card'],
                        'line_items' => [[
                            'price_data' => [
                                'currency' => 'inr',
                                'product_data' => [
                                    'name' => 'Order #' . $order->id,
                                ],
                                'unit_amount' => intval($checkout['total'] * 100), // Convert to paisa
                            ],
                            'quantity' => 1,
                        ]],
                        'mode' => 'payment',
                        'success_url' => route('checkout.success'),
                        'cancel_url' => route('checkout.cancel'),
                    ]);

                    return redirect($session->url);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Unable to process payment. Please try again.');
                }

            default:
                return redirect()->back()->with('error', 'Invalid payment method');
        }
    }

    public function success()
    {
        if (!session()->has('success')) {
            return redirect()->route('home');
        }

        // Clear cart and checkout session if they still exist
        session()->forget(['cart', 'checkout']);
        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
