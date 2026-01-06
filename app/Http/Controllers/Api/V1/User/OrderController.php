<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getUserOrders($request->user()->id, 10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    public function show(Request $request, string $orderNumber): JsonResponse
    {
        $order = $this->orderService->getOrderDetail($request->user()->id, $orderNumber);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
            'payment_channel' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $order = $this->orderService->createOrder($request->user(), [
                'address_id' => $request->address_id,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'payment_method' => $request->payment_method,
                'payment_channel' => $request->payment_channel,
                'notes' => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function cancel(Request $request, string $orderNumber): JsonResponse
    {
        $order = $this->orderService->getOrderDetail($request->user()->id, $orderNumber);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ], 404);
        }

        try {
            $this->orderService->cancelOrder($order);
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
