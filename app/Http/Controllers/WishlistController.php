<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Product::whereHas('wishlists', function($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        // Apply price filters if present
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort by price
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
            }
        }

        $items = $query->get();

        return view('wishlist', [
            'items' => $items,
            'min_price' => $items->min('price'),
            'max_price' => $items->max('price'),
            'current_min' => $request->min_price,
            'current_max' => $request->max_price,
            'sort' => $request->sort
        ]);
    }

    public function preview()
    {
        if (!auth()->check()) {
            return response()->json([
                'items' => [],
                'message' => 'Please login to view your wishlist'
            ]);
        }

        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with('product')  // Eager load products
            ->take(3)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'price' => number_format($item->product->price, 2),
                    'image' => $item->product->image
                ];
            });

        return response()->json([
            'items' => $wishlistItems,
            'total_count' => Wishlist::where('user_id', auth()->id())->count()
        ]);
    }

    public function toggle(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to add items to wishlist'], 401);
        }

        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $user = auth()->user();
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            Wishlist::where('user_id', $user->id)
                ->where('product_id', $request->product_id)
                ->delete();
            
            $wishlistCount = Wishlist::where('user_id', $user->id)->count();
            
            return response()->json([
                'message' => 'Removed from wishlist',
                'status' => 'removed',
                'wishlist_count' => $wishlistCount
            ]);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ]);
            
            $wishlistCount = Wishlist::where('user_id', $user->id)->count();
            
            return response()->json([
                'message' => 'Added to wishlist',
                'status' => 'added',
                'wishlist_count' => $wishlistCount
            ]);
        }
    }

    public function check($productId)
    {
        if (!auth()->check()) {
            return response()->json(['inWishlist' => false]);
        }

        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json(['inWishlist' => $exists]);
    }

    public function addAllToCart()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to continue'], 401);
        }

        $user = auth()->user();
        $wishlistItems = Wishlist::where('user_id', $user->id)->pluck('product_id');
        
        $cart = session()->get('cart', []);
        
        foreach ($wishlistItems as $productId) {
            if (isset($cart[$productId])) {
                $cart[$productId]++;
            } else {
                $cart[$productId] = 1;
            }
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'message' => 'All items added to cart',
            'cart_count' => array_sum($cart)
        ]);
    }

    public function removeMultiple(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer'
        ]);

        $user = auth()->user();
        Wishlist::where('user_id', $user->id)
            ->whereIn('product_id', $request->product_ids)
            ->delete();

        return response()->json([
            'message' => 'Items removed from wishlist',
            'wishlist_count' => Wishlist::where('user_id', $user->id)->count()
        ]);
    }
}
