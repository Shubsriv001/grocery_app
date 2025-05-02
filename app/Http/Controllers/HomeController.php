<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        // No dependencies needed
    }

    public function index(Request $request)
    {
        $query = Product::query();

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Apply price filters
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }

        $products = $query->paginate(16)->withQueryString();

        // Get wishlist items for authenticated user
        $wishlistItems = [];
        if (Auth::check()) {
            $wishlistItems = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        // Get all unique categories for filter
        $categories = Product::distinct()
            ->orderBy('category')
            ->pluck('category');

        $priceRange = [
            'min' => Product::min('price'),
            'max' => Product::max('price')
        ];

        return view('home', [
            'products' => $products,
            'min_price' => $priceRange['min'],
            'max_price' => $priceRange['max'],
            'current_min' => $request->min_price,
            'current_max' => $request->max_price,
            'sort' => $request->sort,
            'wishlistItems' => $wishlistItems,
            'categories' => $categories,
            'priceRange' => $priceRange
        ]);
    }
}
