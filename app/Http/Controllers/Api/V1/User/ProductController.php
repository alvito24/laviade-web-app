<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category_id', 'min_price', 'max_price', 'search', 'sort']);
        $products = $this->productService->getFiltered($filters, $request->per_page ?? 12);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $product = $this->productService->getBySlug($slug);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $keyword = $request->get('q', '');

        if (strlen($keyword) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $products = $this->productService->search($keyword, 10);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function newArrivals(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->productService->getNewArrivals(8),
        ]);
    }

    public function bestSellers(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->productService->getBestSellers(8),
        ]);
    }
}
