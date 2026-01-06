<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'payment', 'shipment', 'address']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,awaiting_payment,payment_confirmed,processing,shipped,delivered,completed,cancelled',
        ]);

        $statusTimestamps = [
            'payment_confirmed' => ['paid_at' => now()],
            'shipped' => ['shipped_at' => now()],
            'delivered' => ['delivered_at' => now()],
            'completed' => ['completed_at' => now()],
            'cancelled' => ['cancelled_at' => now()],
        ];

        $updateData = ['status' => $request->status];
        if (isset($statusTimestamps[$request->status])) {
            $updateData = array_merge($updateData, $statusTimestamps[$request->status]);
        }

        $order->update($updateData);

        // Update payment status if needed
        if ($request->status === 'payment_confirmed' && $order->payment) {
            $order->payment->update([
                'status' => 'confirmed',
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'courier' => 'required|string',
            'service' => 'nullable|string',
            'tracking_number' => 'required|string',
        ]);

        $shipment = $order->shipment;

        if ($shipment) {
            $shipment->update([
                'courier' => $request->courier,
                'service' => $request->service,
                'tracking_number' => $request->tracking_number,
                'status' => 'picked_up',
                'shipped_at' => now(),
            ]);
        } else {
            Shipment::create([
                'order_id' => $order->id,
                'courier' => $request->courier,
                'service' => $request->service,
                'tracking_number' => $request->tracking_number,
                'status' => 'picked_up',
                'shipping_cost' => $order->shipping_cost,
                'recipient_name' => $order->address->recipient_name,
                'recipient_phone' => $order->address->phone,
                'recipient_address' => $order->address->full_address,
                'shipped_at' => now(),
            ]);
        }

        $order->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);

        return back()->with('success', 'Resi pengiriman berhasil diupdate');
    }
}
