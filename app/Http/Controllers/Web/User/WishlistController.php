<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlistService
    ) {
    }

    public function toggle(int $productId): JsonResponse
    {
        $result = $this->wishlistService->toggle(auth()->id(), $productId);

        return response()->json([
            'success' => true,
            'added' => $result['added'],
            'message' => $result['message'],
            'wishlist_count' => $this->wishlistService->getCount(auth()->id()),
        ]);
    }

    public function remove(int $productId): JsonResponse
    {
        $this->wishlistService->remove(auth()->id(), $productId);

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari wishlist',
            'wishlist_count' => $this->wishlistService->getCount(auth()->id()),
        ]);
    }

    public function check(int $productId): JsonResponse
    {
        return response()->json([
            'in_wishlist' => $this->wishlistService->isInWishlist(auth()->id(), $productId),
        ]);
    }
}
