<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, CartService $cart)
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->where('is_active', true)])->get();

        $query = Product::where('is_active', true)->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                default      => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop.index', compact('products', 'categories', 'cart'));
    }

    public function show(string $slug, CartService $cart)
    {
        $product  = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related  = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related', 'cart'));
    }
}
