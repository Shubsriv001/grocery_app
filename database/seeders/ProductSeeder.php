<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Fruits & Vegetables
            [
                'name' => 'Fresh Apples',
                'description' => 'Sweet and crispy red apples',
                'price' => 2.99,
                'image' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6',
                'category' => 'Fruits',
                'stock' => 100
            ],
            [
                'name' => 'Organic Bananas',
                'description' => 'Fresh organic bananas, per bunch',
                'price' => 1.99,
                'image' => 'https://images.unsplash.com/photo-1528825871115-3581a5387919',
                'category' => 'Fruits',
                'stock' => 150
            ],
            [
                'name' => 'Fresh Carrots',
                'description' => 'Organic carrots, 1lb bag',
                'price' => 1.99,
                'image' => 'https://images.unsplash.com/photo-1447175008436-054170c2e979',
                'category' => 'Vegetables',
                'stock' => 150
            ],
            [
                'name' => 'Spinach',
                'description' => 'Fresh organic spinach, 500g',
                'price' => 2.49,
                'image' => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb',
                'category' => 'Vegetables',
                'stock' => 100
            ],
            // Dairy & Eggs
            [
                'name' => 'Organic Milk',
                'description' => '1 gallon of organic whole milk',
                'price' => 4.99,
                'image' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150',
                'category' => 'Dairy',
                'stock' => 75
            ],
            [
                'name' => 'Fresh Eggs',
                'description' => 'Farm fresh eggs, dozen',
                'price' => 3.99,
                'image' => 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f',
                'category' => 'Dairy',
                'stock' => 100
            ],
            // Bakery
            [
                'name' => 'Whole Wheat Bread',
                'description' => 'Freshly baked whole wheat bread',
                'price' => 3.49,
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff',
                'category' => 'Bakery',
                'stock' => 50
            ],
            [
                'name' => 'Croissants',
                'description' => 'Butter croissants, 4 pack',
                'price' => 4.99,
                'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a',
                'category' => 'Bakery',
                'stock' => 40
            ],
            // Meat & Seafood
            [
                'name' => 'Chicken Breast',
                'description' => 'Fresh boneless chicken breast, 1lb',
                'price' => 5.99,
                'image' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82137',
                'category' => 'Meat',
                'stock' => 60
            ],
            [
                'name' => 'Atlantic Salmon',
                'description' => 'Fresh Atlantic salmon fillet, 1lb',
                'price' => 12.99,
                'image' => 'https://images.unsplash.com/photo-1599084993091-1cb5c0932b41',
                'category' => 'Seafood',
                'stock' => 40
            ],
            // Pantry
            [
                'name' => 'Pasta',
                'description' => 'Italian spaghetti, 500g',
                'price' => 2.49,
                'image' => 'https://images.unsplash.com/photo-1551462147-37885acc36f1',
                'category' => 'Pantry',
                'stock' => 200
            ],
            [
                'name' => 'Rice',
                'description' => 'Premium basmati rice, 2kg',
                'price' => 6.99,
                'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c',
                'category' => 'Pantry',
                'stock' => 150
            ],
            // Beverages
            [
                'name' => 'Orange Juice',
                'description' => 'Fresh squeezed orange juice, 1L',
                'price' => 4.99,
                'image' => 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b',
                'category' => 'Beverages',
                'stock' => 80
            ],
            [
                'name' => 'Green Tea',
                'description' => 'Organic green tea, 20 bags',
                'price' => 3.99,
                'image' => 'https://images.unsplash.com/photo-1627435601361-ec25f5b1d0e5',
                'category' => 'Beverages',
                'stock' => 100
            ],
            // Snacks
            [
                'name' => 'Mixed Nuts',
                'description' => 'Premium mixed nuts, 500g',
                'price' => 8.99,
                'image' => 'https://images.unsplash.com/photo-1606923829579-0cb981a83e2e',
                'category' => 'Snacks',
                'stock' => 120
            ],
            [
                'name' => 'Dark Chocolate',
                'description' => '70% dark chocolate bar, 100g',
                'price' => 3.99,
                'image' => 'https://images.unsplash.com/photo-1548907040-4d42bea34b39',
                'category' => 'Snacks',
                'stock' => 150
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
