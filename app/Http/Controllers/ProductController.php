<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        return Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'category' => $product->category,
                'stock' => $product->stock,
                'description' => $product->description
            ];
        })->toArray();
    }

    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filter by category
        if ($request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Search by name
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Sort products
        $validSortFields = ['name', 'price', 'category'];
        $sort = in_array($request->get('sort'), $validSortFields) ? $request->get('sort') : 'name';
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sort, $direction);

        // Get products with pagination
        $products = $query->paginate(16)->withQueryString();

        // Get categories for filter
        $categories = Product::distinct()->pluck('category');

        return view('home', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('home', compact('product'));
    }

    public function getCategories()
    {
        return Product::distinct()->pluck('category')
            ->mapWithKeys(function ($category) {
                return [$category => ucfirst($category)];
            })
            ->prepend('All Categories', 'all')
            ->toArray();
    }
}
