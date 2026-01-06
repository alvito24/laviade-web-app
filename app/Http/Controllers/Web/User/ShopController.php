<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['category_id', 'min_price', 'max_price', 'search', 'sort']);
        $products = $this->productService->getFiltered($filters, 12);
        $categories = Category::active()->parent()->ordered()->get();

        return view('user.shop.index', compact('products', 'categories', 'filters'));
    }

    public function show(string $slug): View
    {
        $product = $this->productService->getBySlug($slug);

        if (!$product) {
            abort(404);
        }

        $relatedProducts = $this->productService->getRelatedProducts($product, 4);
        $bestSellers = $this->productService->getBestSellers(4);

        return view('user.shop.show', compact('product', 'relatedProducts', 'bestSellers'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q', '');

        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $products = $this->productService->search($keyword, 10);

        return response()->json($products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => $p->formatted_current_price,
            'image' => $p->primary_image_url,
        ]));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $this->productService->getByCategory($category->id, 12);
        $categories = Category::active()->parent()->ordered()->get();

        return view('user.shop.index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => ['category_id' => $category->id],
            'currentCategory' => $category,
        ]);
    }
}
