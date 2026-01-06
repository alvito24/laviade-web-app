<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected CartService $cartService
    ) {
    }

    public function createOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $selectedItems = $this->cartService->getSelectedItems($user->id);

            if (empty($selectedItems['items'])) {
                throw new \Exception('Tidak ada item yang dipilih di keranjang');
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $data['address_id'],
                'status' => 'pending',
                'subtotal' => $selectedItems['subtotal'],
                'shipping_cost' => $data['shipping_cost'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'total' => $selectedItems['subtotal'] + ($data['shipping_cost'] ?? 0) - ($data['discount'] ?? 0),
                'shipping_method' => $data['shipping_method'] ?? null,
                'notes' => $data['notes'] ?? null,
                'ordered_at' => now(),
            ]);

            // Create order items
            foreach ($selectedItems['items'] as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_image' => $cartItem->product->primaryImage?->image,
                    'size' => $cartItem->size,
                    'color' => $cartItem->color,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);

                // Reduce stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
                $cartItem->product->increment('total_sold', $cartItem->quantity);
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $data['payment_method'],
                'payment_channel' => $data['payment_channel'] ?? null,
                'amount' => $order->total,
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);

            // Clear selected cart items
            $this->cartService->clearSelectedItems($user->id);

            return $order->load(['items', 'payment', 'address']);
        });
    }

    public function getUserOrders(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Order::with(['items', 'payment'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getOrderDetail(int $userId, string $orderNumber): ?Order
    {
        return Order::with(['items.product', 'payment', 'shipment', 'address'])
            ->where('user_id', $userId)
            ->where('order_number', $orderNumber)
            ->first();
    }

    public function confirmPayment(Order $order, array $data): Payment
    {
        $payment = $order->payment;

        $payment->update([
            'status' => 'waiting_confirmation',
            'payment_proof' => $data['payment_proof'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
        ]);

        $order->update(['status' => 'awaiting_payment']);

        return $payment->fresh();
    }

    public function cancelOrder(Order $order): Order
    {
        if (!in_array($order->status, ['pending', 'awaiting_payment'])) {
            throw new \Exception('Pesanan tidak dapat dibatalkan');
        }

        DB::transaction(function () use ($order) {
            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
                $item->product->decrement('total_sold', $item->quantity);
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            $order->payment?->update(['status' => 'failed']);
        });

        return $order->fresh();
    }

    public function getOrdersByStatus(int $userId, string $status): LengthAwarePaginator
    {
        return Order::with(['items', 'payment'])
            ->where('user_id', $userId)
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->paginate(10);
    }
}
