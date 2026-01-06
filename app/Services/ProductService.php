<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getActiveProducts(int $perPage = 12): LengthAwarePaginator
    {
        return Product::with(['primaryImage', 'category'])
            ->active()
            ->inStock()
            ->latest()
            ->paginate($perPage);
    }

    public function getNewArrivals(int $limit = 8): Collection
    {
        return Product::with(['primaryImage', 'category'])
            ->active()
            ->inStock()
            ->newArrival()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getBestSellers(int $limit = 8): Collection
    {
        return Product::with(['primaryImage', 'category'])
            ->active()
            ->inStock()
            ->bestSeller()
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    public function getFeatured(int $limit = 8): Collection
    {
        return Product::with(['primaryImage', 'category'])
            ->active()
            ->inStock()
            ->featured()
            ->limit($limit)
            ->get();
    }

    public function getByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        return Product::with(['primaryImage', 'category'])
            ->active()
            ->inStock()
            ->byCategory($categoryId)
            ->latest()
            ->paginate($perPage);
    }

    public function getFiltered(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with(['primaryImage', 'category'])
            ->active()
            ->inStock();

        if (!empty($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }

        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $minPrice = $filters['min_price'] ?? 0;
            $maxPrice = $filters['max_price'] ?? PHP_INT_MAX;
            $query->priceRange($minPrice, $maxPrice);
        }

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        $sortBy = $filters['sort'] ?? 'latest';
        $query = match ($sortBy) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('total_sold'),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }

    public function search(string $keyword, int $limit = 10): Collection
    {
        return Product::with(['primaryImage'])
            ->active()
            ->search($keyword)
            ->limit($limit)
            ->get();
    }

    public function getBySlug(string $slug): ?Product
    {
        $product = Product::with(['images', 'category', 'reviews.user'])
            ->active()
            ->where('slug', $slug)
            ->first();

        if ($product) {
            $product->incrementViewCount();
        }

        return $product;
    }

    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        return Product::with(['primaryImage'])
            ->active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit($limit)
            ->get();
    }
}
