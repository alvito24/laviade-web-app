<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $selectedItems = $this->cartService->getSelectedItems($user->id);

        if (empty($selectedItems['items']) || $selectedItems['items']->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih produk terlebih dahulu untuk checkout');
        }

        $addresses = $user->addresses()->orderByDesc('is_primary')->get();
        $primaryAddress = $user->primaryAddress;

        return view('user.checkout.index', compact('selectedItems', 'addresses', 'primaryAddress'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
            'payment_channel' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $order = $this->orderService->createOrder(auth()->user(), [
                'address_id' => $request->address_id,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $request->shipping_cost ?? 0,
                'payment_method' => $request->payment_method,
                'payment_channel' => $request->payment_channel,
                'notes' => $request->notes,
            ]);

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(string $orderNumber): View
    {
        $order = $this->orderService->getOrderDetail(auth()->id(), $orderNumber);

        if (!$order) {
            abort(404);
        }

        return view('user.checkout.success', compact('order'));
    }
}
