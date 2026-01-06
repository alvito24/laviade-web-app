<x-layouts.app title="Shopping Cart">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-8">Shopping Cart</h1>

        @if($cart && $cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <!-- Select All -->
                    <div class="bg-surface rounded-lg p-4 mb-4 flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="select-all" class="w-5 h-5 rounded" {{ $cart->items->every(fn($i) => $i->is_selected) ? 'checked' : '' }}>
                            <span class="ml-3 font-medium">Select All ({{ $cart->items->count() }} items)</span>
                        </label>
                        <button onclick="clearCart()" class="text-red-600 hover:text-red-800 text-sm">
                            Delete Selected
                        </button>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        @foreach($cart->items as $item)
                            <div class="bg-surface rounded-lg p-4" id="cart-item-{{ $item->id }}">
                                <div class="flex gap-4">
                                    <!-- Checkbox -->
                                    <div class="flex items-start pt-2">
                                        <input type="checkbox" class="item-checkbox w-5 h-5 rounded" data-id="{{ $item->id }}"
                                            {{ $item->is_selected ? 'checked' : '' }}>
                                    </div>

                                    <!-- Image -->
                                    <a href="{{ route('shop.show', $item->product->slug) }}" class="flex-shrink-0">
                                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}"
                                            class="w-24 h-24 object-cover rounded">
                                    </a>

                                    <!-- Details -->
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <a href="{{ route('shop.show', $item->product->slug) }}"
                                                    class="font-medium hover:underline">
                                                    {{ $item->product->name }}
                                                </a>
                                                <p class="text-sm text-secondary mt-1">{{ $item->formatted_price }}</p>
                                            </div>
                                            <button onclick="removeItem({{ $item->id }})"
                                                class="text-secondary hover:text-red-600 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Size & Color -->
                                        <div class="flex gap-4 mt-2 text-sm">
                                            @if($item->size)
                                                <div>
                                                    <span class="text-secondary">Size:</span>
                                                    <select class="ml-1 bg-white border border-custom rounded px-2 py-1"
                                                        onchange="updateSize({{ $item->id }}, this.value)">
                                                        @foreach($item->product->sizes ?? [] as $size)
                                                            <option value="{{ $size }}" {{ $item->size === $size ? 'selected' : '' }}>
                                                                {{ $size }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            @if($item->color)
                                                <div>
                                                    <span class="text-secondary">Color:</span>
                                                    <span class="ml-1">{{ $item->color }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Quantity -->
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center border border-custom rounded">
                                                <button onclick="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    class="px-3 py-1 hover:bg-white transition">-</button>
                                                <span class="px-4 py-1 border-x border-custom"
                                                    id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                                <button onclick="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    class="px-3 py-1 hover:bg-white transition">+</button>
                                            </div>
                                            <span class="font-semibold"
                                                id="subtotal-{{ $item->id }}">{{ $item->formatted_subtotal }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-surface rounded-lg p-6 sticky top-24">
                        <h2 class="text-lg font-bold mb-4">Order Summary</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-secondary">
                                <span>Subtotal</span>
                                <span id="cart-subtotal">{{ $cart->formatted_subtotal }}</span>
                            </div>
                            <div class="flex justify-between text-secondary">
                                <span>Selected Items</span>
                                <span id="selected-subtotal">{{ $cart->formatted_selected_subtotal }}</span>
                            </div>
                        </div>

                        <div class="border-t border-custom pt-4 mb-6">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span id="cart-total">{{ $cart->formatted_selected_subtotal }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                            class="btn-primary w-full block text-center py-4 rounded-lg font-semibold">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('shop.index') }}"
                            class="block text-center mt-4 text-secondary hover:text-black transition">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h2 class="text-xl font-bold mb-2">Your cart is empty</h2>
                <p class="text-secondary mb-6">Looks like you haven't added anything to your cart yet</p>
                <a href="{{ route('shop.index') }}" class="btn-primary inline-block rounded-lg">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // Select All
            document.getElementById('select-all')?.addEventListener('change', async function () {
                const selected = this.checked;
                document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = selected);

                await fetch('/cart/select-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ selected })
                });
                location.reload();
            });

            // Toggle Item
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.addEventListener('change', async function () {
                    await fetch(`/cart/${this.dataset.id}/toggle`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    location.reload();
                });
            });

            async function updateQty(id, qty) {
                if (qty < 1) {
                    if (confirm('Hapus item ini dari keranjang?')) {
                        removeItem(id);
                    }
                    return;
                }

                await fetch(`/cart/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ quantity: qty })
                });
                location.reload();
            }

            async function updateSize(id, size) {
                await fetch(`/cart/${id}/size`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ size })
                });
            }

            async function removeItem(id) {
                await fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                document.getElementById(`cart-item-${id}`)?.remove();
                location.reload();
            }
        </script>
    @endpush
</x-layouts.app>