<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlistService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $wishlist = $this->wishlistService->getUserWishlist($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => $wishlist,
        ]);
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $result = $this->wishlistService->toggle(
            $request->user()->id,
            $request->product_id
        );

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'added' => $result['added'],
                'count' => $this->wishlistService->getCount($request->user()->id),
            ],
        ]);
    }

    public function check(Request $request, int $productId): JsonResponse
    {
        $isWishlisted = $this->wishlistService->isInWishlist($request->user()->id, $productId);

        return response()->json([
            'success' => true,
            'data' => [
                'is_wishlisted' => $isWishlisted,
            ],
        ]);
    }
}
