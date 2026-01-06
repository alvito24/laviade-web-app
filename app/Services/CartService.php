<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart(int $userId): Cart
    {
        return Cart::with(['items.product.primaryImage'])
            ->firstOrCreate(['user_id' => $userId]);
    }

    public function addToCart(int $userId, int $productId, int $quantity = 1, ?string $size = null, ?string $color = null): CartItem
    {
        $cart = Cart::getOrCreateForUser($userId);
        $product = Product::findOrFail($productId);

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        if ($existingItem) {
            $newQuantity = min($existingItem->quantity + $quantity, $product->stock);
            $existingItem->update(['quantity' => $newQuantity]);
            return $existingItem->fresh();
        }

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'size' => $size,
            'color' => $color,
            'quantity' => min($quantity, $product->stock),
            'price' => $product->current_price,
            'is_selected' => true,
        ]);
    }

    public function updateQuantity(int $userId, int $cartItemId, int $quantity): ?CartItem
    {
        $cartItem = CartItem::whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->find($cartItemId);

        if (!$cartItem) {
            return null;
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return null;
        }

        $cartItem->update([
            'quantity' => min($quantity, $cartItem->product->stock)
        ]);

        return $cartItem->fresh();
    }

    public function updateSize(int $userId, int $cartItemId, string $size): ?CartItem
    {
        $cartItem = CartItem::whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->find($cartItemId);

        if ($cartItem) {
            $cartItem->update(['size' => $size]);
        }

        return $cartItem?->fresh();
    }

    public function removeItem(int $userId, int $cartItemId): bool
    {
        return CartItem::whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->where('id', $cartItemId)
            ->delete() > 0;
    }

    public function toggleItemSelection(int $userId, int $cartItemId): ?CartItem
    {
        $cartItem = CartItem::whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->find($cartItemId);

        if ($cartItem) {
            $cartItem->update(['is_selected' => !$cartItem->is_selected]);
        }

        return $cartItem?->fresh();
    }

    public function selectAll(int $userId, bool $selected = true): void
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->update(['is_selected' => $selected]);
        }
    }

    public function getSelectedItems(int $userId): array
    {
        $cart = Cart::with(['selectedItems.product.primaryImage'])
            ->where('user_id', $userId)
            ->first();

        if (!$cart) {
            return ['items' => [], 'subtotal' => 0];
        }

        return [
            'items' => $cart->selectedItems,
            'subtotal' => $cart->selected_subtotal,
        ];
    }

    public function clearCart(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }
    }

    public function clearSelectedItems(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->where('is_selected', true)->delete();
        }
    }
}
