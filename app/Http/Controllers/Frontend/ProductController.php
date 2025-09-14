<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // app/Http/Controllers/Frontend/ProductController.php

    public function index(Request $request)
    {
        $currentCategory = $request->string('category', 'All')->toString();
        $q               = $request->string('q')->toString();
        $sort            = $request->string('sort', 'Newest')->toString();

        $minReq  = $request->filled('min_price') ? (int) $request->input('min_price') : null;
        $maxReq  = $request->filled('max_price') ? (int) $request->input('max_price') : null;
        $inStock = $request->boolean('in_stock');
        $outStock = $request->boolean('out_of_stock');

        $categories = Category::orderBy('name')->pluck('name')->toArray();
        array_unshift($categories, 'All');

        $query = Product::query()
            ->with(['category', 'components'])        // components for packages
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        $query->categoryName($currentCategory)
            ->search($q)
            ->priceBetweenEffective($minReq, $maxReq)
            ->availability($inStock, $outStock);

        switch ($sort) {
            case 'Price: Low to High':
                $query->orderByListPrice('asc');
                break;
            case 'Price: High to Low':
                $query->orderByListPrice('desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $products->getCollection()->transform(function ($p) {
            $p->rating = $p->reviews_avg_rating ? (int) round($p->reviews_avg_rating) : null;
            return $p;
        });

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'currentCategory',
            'q',
            'sort',
            'minReq',
            'maxReq',
            'inStock',
            'outStock'
        ));
    }

    // Optional dedicated packages page:
    public function packages(Request $request)
    {
        $request->merge(['category' => $request->input('category', 'All')]);
        $q = $request->string('q')->toString();

        $products = Product::packages()
            ->with(['category', 'components'])
            ->search($q)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('frontend.products.packages', compact('products', 'q'));
    }



    public function show($id)
    {
        $product = Product::withCount('reviews as reviews_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->findOrFail($id);

        // Simple “related”: same category, exclude self
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.products.show', compact('product', 'related'));
    }
}
