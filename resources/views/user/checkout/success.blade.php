<x-layouts.app title="Order Success">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold mb-4">Pesanan Berhasil!</h1>
        <p class="text-secondary mb-8">
            Terima kasih telah berbelanja di LAVIADE. Pesanan Anda sedang diproses.
        </p>

        <div class="bg-surface rounded-lg p-6 text-left mb-8">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-secondary">Order Number</span>
                    <div class="font-bold text-lg">{{ $order->order_number }}</div>
                </div>
                <div>
                    <span class="text-secondary">Total</span>
                    <div class="font-bold text-lg">{{ $order->formatted_total }}</div>
                </div>
                <div>
                    <span class="text-secondary">Payment Method</span>
                    <div class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</div>
                </div>
                <div>
                    <span class="text-secondary">Status</span>
                    <div class="font-medium">
                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            @if($order->payment->payment_method === 'bank_transfer')
                <div class="mt-6 pt-6 border-t border-custom">
                    <h3 class="font-semibold mb-3">Payment Instructions</h3>
                    <p class="text-secondary text-sm mb-4">
                        Silakan transfer ke salah satu rekening berikut dalam waktu 24 jam:
                    </p>
                    <div class="space-y-3">
                        <div class="bg-white p-3 rounded">
                            <div class="font-medium">Bank BCA</div>
                            <div class="text-lg font-bold">1234567890</div>
                            <div class="text-sm text-secondary">a.n. PT Laviade Indonesia</div>
                        </div>
                        <div class="bg-white p-3 rounded">
                            <div class="font-medium">Bank Mandiri</div>
                            <div class="text-lg font-bold">0987654321</div>
                            <div class="text-sm text-secondary">a.n. PT Laviade Indonesia</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('profile.orders.show', $order->order_number) }}" class="btn-primary rounded-lg">
                View Order Details
            </a>
            <a href="{{ route('shop.index') }}" class="btn-secondary rounded-lg">
                Continue Shopping
            </a>
        </div>
    </div>
</x-layouts.app>