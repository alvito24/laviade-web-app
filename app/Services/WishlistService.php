<?php

namespace App\Services;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

class WishlistService
{
    public function getUserWishlist(int $userId): Collection
    {
        return Wishlist::with(['product.primaryImage', 'product.category'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function toggle(int $userId, int $productId): array
    {
        $added = Wishlist::toggle($userId, $productId);

        return [
            'added' => $added,
            'message' => $added ? 'Produk ditambahkan ke wishlist' : 'Produk dihapus dari wishlist',
        ];
    }

    public function add(int $userId, int $productId): Wishlist
    {
        return Wishlist::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    public function remove(int $userId, int $productId): bool
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete() > 0;
    }

    public function isInWishlist(int $userId, int $productId): bool
    {
        return Wishlist::isWishlisted($userId, $productId);
    }

    public function getCount(int $userId): int
    {
        return Wishlist::where('user_id', $userId)->count();
    }

    public function clear(int $userId): void
    {
        Wishlist::where('user_id', $userId)->delete();
    }
}
