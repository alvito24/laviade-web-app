<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => [
                'cart' => $cart,
                'items' => $cart->items,
                'subtotal' => $cart->subtotal,
                'selected_subtotal' => $cart->selected_subtotal,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $cartItem = $this->cartService->addToCart(
            $request->user()->id,
            $request->product_id,
            $request->quantity ?? 1,
            $request->size,
            $request->color
        );

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang',
            'data' => $cartItem->load('product'),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $cartItem = $this->cartService->updateQuantity(
            $request->user()->id,
            $id,
            $request->quantity
        );

        $cart = $this->cartService->getCart($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui',
            'data' => [
                'item' => $cartItem,
                'subtotal' => $cart->subtotal,
                'selected_subtotal' => $cart->selected_subtotal,
            ],
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->cartService->removeItem($request->user()->id, $id);
        $cart = $this->cartService->getCart($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang',
            'data' => [
                'subtotal' => $cart->subtotal,
                'selected_subtotal' => $cart->selected_subtotal,
            ],
        ]);
    }

    public function toggleSelection(Request $request, int $id): JsonResponse
    {
        $this->cartService->toggleItemSelection($request->user()->id, $id);
        $cart = $this->cartService->getCart($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => [
                'selected_subtotal' => $cart->selected_subtotal,
            ],
        ]);
    }
}
