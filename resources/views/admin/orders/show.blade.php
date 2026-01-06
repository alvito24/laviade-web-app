<x-layouts.admin title="Order Detail">
    <div class="max-w-5xl">
        <a href="{{ route('admin.orders.index') }}"
            class="text-gray-500 hover:text-black mb-4 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Orders
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
            <!-- Order Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-bold">{{ $order->order_number }}</h2>
                            <p class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                            {{ in_array($order->status, ['pending', 'awaiting_payment']) ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-blue-100 text-blue-700' : '' }}
                        ">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>

                    <h3 class="font-semibold mb-3">Order Items</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 py-3 border-b last:border-0">
                                <img src="{{ $item->product_image_url }}" class="w-16 h-16 rounded object-cover">
                                <div class="flex-1">
                                    <div class="font-medium">{{ $item->product_name }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($item->product_size) Size: {{ $item->product_size }} @endif
                                        @if($item->product_color) | {{ $item->product_color }} @endif
                                    </div>
                                    <div class="text-sm">{{ $item->quantity }} x Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                                <div class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t space-y-2">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Shipping</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between font-bold text-lg pt-2 border-t">
                            <span>Total</span>
                            <span>{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold mb-3">Shipping Address</h3>
                    <p class="font-medium">{{ $order->shipping_name }}</p>
                    <p class="text-gray-500">{{ $order->shipping_phone }}</p>
                    <p class="text-gray-500 mt-1">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div class="space-y-6">
                <!-- Update Status -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold mb-4">Update Status</h3>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="w-full px-3 py-2 border rounded-lg mb-3">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="awaiting_payment" {{ $order->status === 'awaiting_payment' ? 'selected' : '' }}>Awaiting Payment</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                        <button type="submit" class="w-full py-2 bg-black text-white rounded-lg hover:bg-gray-800">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Tracking -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold mb-4">Shipping Tracking</h3>
                    <form action="{{ route('admin.orders.tracking', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm mb-1">Courier</label>
                                <input type="text" name="courier" value="{{ $order->shipment?->courier }}"
                                    class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Tracking Number</label>
                                <input type="text" name="tracking_number"
                                    value="{{ $order->shipment?->tracking_number }}"
                                    class="w-full px-3 py-2 border rounded-lg">
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full mt-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save Tracking
                        </button>
                    </form>
                </div>

                <!-- Customer Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold mb-4">Customer</h3>
                    <div class="flex items-center gap-3">
                        <img src="{{ $order->user->avatar_url }}" class="w-10 h-10 rounded-full">
                        <div>
                            <div class="font-medium">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold mb-4">Payment</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Method</span>
                            <span>{{ ucfirst(str_replace('_', ' ', $order->payment?->payment_method ?? '-')) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span
                                class="px-2 py-0.5 rounded text-xs {{ $order->payment?->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($order->payment?->status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>