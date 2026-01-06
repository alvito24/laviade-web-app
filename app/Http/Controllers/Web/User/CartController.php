<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {
    }

    public function index(): View
    {
        $cart = $this->cartService->getCart(auth()->id());
        return view('user.cart.index', compact('cart'));
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:10',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $cartItem = $this->cartService->addToCart(
            auth()->id(),
            $request->product_id,
            $request->quantity ?? 1,
            $request->size,
            $request->color
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang',
                'cart_count' => auth()->user()->cart_items_count,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $cartItem = $this->cartService->updateQuantity(
            auth()->id(),
            $id,
            $request->quantity
        );

        $cart = $this->cartService->getCart(auth()->id());

        return response()->json([
            'success' => true,
            'item' => $cartItem,
            'subtotal' => $cart->formatted_subtotal,
            'selected_subtotal' => $cart->formatted_selected_subtotal,
            'cart_count' => $cart->total_items,
        ]);
    }

    public function updateSize(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'size' => 'required|string',
        ]);

        $cartItem = $this->cartService->updateSize(auth()->id(), $id, $request->size);

        return response()->json([
            'success' => true,
            'item' => $cartItem,
        ]);
    }

    public function remove(int $id): JsonResponse
    {
        $this->cartService->removeItem(auth()->id(), $id);
        $cart = $this->cartService->getCart(auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang',
            'subtotal' => $cart->formatted_subtotal,
            'selected_subtotal' => $cart->formatted_selected_subtotal,
            'cart_count' => $cart->total_items,
        ]);
    }

    public function toggleSelection(int $id): JsonResponse
    {
        $this->cartService->toggleItemSelection(auth()->id(), $id);
        $cart = $this->cartService->getCart(auth()->id());

        return response()->json([
            'success' => true,
            'selected_subtotal' => $cart->formatted_selected_subtotal,
        ]);
    }

    public function selectAll(Request $request): JsonResponse
    {
        $this->cartService->selectAll(auth()->id(), $request->boolean('selected', true));
        $cart = $this->cartService->getCart(auth()->id());

        return response()->json([
            'success' => true,
            'selected_subtotal' => $cart->formatted_selected_subtotal,
        ]);
    }
}
