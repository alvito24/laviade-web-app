<x-layouts.app title="Checkout">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-8">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Address -->
                    <div class="bg-surface rounded-lg p-6">
                        <h2 class="text-lg font-bold mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
                            Shipping Address
                        </h2>

                        @if($addresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($addresses as $address)
                                    <label
                                        class="block p-4 border border-custom rounded-lg cursor-pointer hover:border-black transition relative">
                                        <input type="radio" name="address_id" value="{{ $address->id }}"
                                            class="absolute top-4 right-4" {{ $address->is_primary ? 'checked' : '' }} required>
                                        <div class="pr-8">
                                            <div class="font-medium">{{ $address->recipient_name }}</div>
                                            <div class="text-sm text-secondary">{{ $address->phone }}</div>
                                            <div class="text-sm text-secondary mt-1">{{ $address->full_address }}</div>
                                            @if($address->is_primary)
                                                <span
                                                    class="inline-block mt-2 text-xs bg-black text-white px-2 py-1 rounded">Primary</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-secondary mb-4">Anda belum memiliki alamat tersimpan</p>
                                <a href="{{ route('profile.addresses') }}" class="btn-primary inline-block rounded-lg">
                                    Tambah Alamat
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Shipping Method -->
                    <div class="bg-surface rounded-lg p-6">
                        <h2 class="text-lg font-bold mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center text-sm mr-3">2</span>
                            Shipping Method
                        </h2>

                        <div class="space-y-3">
                            <label
                                class="block p-4 border border-custom rounded-lg cursor-pointer hover:border-black transition">
                                <input type="radio" name="shipping_method" value="jne_reg" class="mr-3" checked
                                    required>
                                <span class="font-medium">JNE Regular</span>
                                <span class="text-secondary text-sm ml-2">(2-3 hari)</span>
                                <span class="float-right font-medium">Rp 15.000</span>
                                <input type="hidden" name="shipping_cost" value="15000">
                            </label>
                            <label
                                class="block p-4 border border-custom rounded-lg cursor-pointer hover:border-black transition">
                                <input type="radio" name="shipping_method" value="jne_yes" class="mr-3" required>
                                <span class="font-medium">JNE YES</span>
                                <span class="text-secondary text-sm ml-2">(1 hari)</span>
                                <span class="float-right font-medium">Rp 25.000</span>
                            </label>
                            <label
                                class="block p-4 border border-custom rounded-lg cursor-pointer hover:border-black transition">
                                <input type="radio" name="shipping_method" value="sicepat" class="mr-3" required>
                                <span class="font-medium">SiCepat REG</span>
                                <span class="text-secondary text-sm ml-2">(2-4 hari)</span>
                                <span class="float-right font-medium">Rp 12.000</span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-surface rounded-lg p-6">
                        <h2 class="text-lg font-bold mb-4 flex items-center">
                            <span
                                class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center text-sm mr-3">3</span>
                            Payment Method
                        </h2>

                        <div class="space-y-3">
                            <label
                                class="block p-4 border border-custom rounded-lg cursor-pointer hover:border-black transition">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mr-3" checked
                                    required>
                                <span class="font-medium">Bank Transfer</span>
                                <div class="mt-2 ml-6 text-sm text-secondary">
                                    Transfer ke rekening BCA / Mandiri / BNI
                                </div>
                            </label>
                            <label
                                class="block p-4 border border-custom rounded-lg cursor-pointer hover:border-black transition">
                                <input type="radio" name="payment_method" value="e-wallet" class="mr-3" required>
                                <span class="font-medium">E-Wallet</span>
                                <div class="mt-2 ml-6 text-sm text-secondary">
                                    Gopay, OVO, DANA, ShopeePay
                                </div>
                            </label>
                            <label
                                class="block p-4 border border-custom rounded-lg cursor-pointer hover:border-black transition">
                                <input type="radio" name="payment_method" value="cod" class="mr-3" required>
                                <span class="font-medium">Cash on Delivery (COD)</span>
                                <div class="mt-2 ml-6 text-sm text-secondary">
                                    Bayar saat barang diterima
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-surface rounded-lg p-6">
                        <label class="block font-medium mb-2">Order Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Catatan untuk pesanan Anda..."
                            class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300"></textarea>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-surface rounded-lg p-6 sticky top-24">
                        <h2 class="text-lg font-bold mb-4">Order Summary</h2>

                        <!-- Items -->
                        <div class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                            @foreach($selectedItems['items'] as $item)
                                <div class="flex gap-3">
                                    <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}"
                                        class="w-16 h-16 object-cover rounded">
                                    <div class="flex-1">
                                        <div class="font-medium text-sm truncate">{{ $item->product->name }}</div>
                                        <div class="text-xs text-secondary">
                                            @if($item->size) Size: {{ $item->size }} @endif
                                            @if($item->color) | {{ $item->color }} @endif
                                        </div>
                                        <div class="text-sm mt-1">{{ $item->quantity }} x {{ $item->formatted_price }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-custom pt-4 space-y-3">
                            <div class="flex justify-between text-secondary">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($selectedItems['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-secondary">
                                <span>Shipping</span>
                                <span id="shipping-display">Rp 15.000</span>
                            </div>
                        </div>

                        <div class="border-t border-custom pt-4 mt-4">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span id="total-display">Rp
                                    {{ number_format($selectedItems['subtotal'] + 15000, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full mt-6 py-4 rounded-lg font-semibold">
                            Place Order
                        </button>

                        <p class="text-xs text-secondary text-center mt-4">
                            Dengan melakukan pemesanan, Anda menyetujui Syarat & Ketentuan kami
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const subtotal = {{ $selectedItems['subtotal'] }};
            const shippingCosts = {
                'jne_reg': 15000,
                'jne_yes': 25000,
                'sicepat': 12000
            };

            document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const cost = shippingCosts[this.value] || 15000;
                    document.getElementById('shipping-display').textContent = 'Rp ' + cost.toLocaleString('id-ID');
                    document.getElementById('total-display').textContent = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');
                });
            });
        </script>
    @endpush
</x-layouts.app>