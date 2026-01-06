<x-layouts.app :title="'Order ' . $order->order_number">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('user.profile.partials.sidebar')

            <div class="flex-1">
                <a href="{{ route('profile.orders') }}"
                    class="text-secondary hover:text-black mb-4 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Orders
                </a>

                <div class="bg-surface rounded-lg p-6 mt-4">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold">{{ $order->order_number }}</h2>
                            <p class="text-secondary">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="mt-2 md:mt-0 px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                            {{ in_array($order->status, ['pending', 'awaiting_payment']) ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-blue-100 text-blue-700' : '' }}
                        ">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>

                    <!-- Order Timeline -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between relative">
                            <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200"></div>
                            @php
                                $steps = ['pending', 'processing', 'shipped', 'delivered', 'completed'];
                                $currentIndex = array_search($order->status, $steps);
                            @endphp
                            @foreach(['Pending', 'Processing', 'Shipped', 'Delivered', 'Completed'] as $i => $step)
                                <div class="relative z-10 flex flex-col items-center">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center {{ $i <= $currentIndex ? 'bg-black text-white' : 'bg-gray-200' }}">
                                        @if($i < $currentIndex)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </div>
                                    <span
                                        class="text-xs mt-1 {{ $i <= $currentIndex ? 'text-black' : 'text-secondary' }}">{{ $step }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Items -->
                    <h3 class="font-semibold mb-4">Order Items</h3>
                    <div class="space-y-4 mb-6">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 bg-white p-4 rounded-lg">
                                <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}"
                                    class="w-20 h-20 object-cover rounded">
                                <div class="flex-1">
                                    <div class="font-medium">{{ $item->product_name }}</div>
                                    <div class="text-sm text-secondary">
                                        @if($item->product_size) Size: {{ $item->product_size }} @endif
                                        @if($item->product_color) | {{ $item->product_color }} @endif
                                    </div>
                                    <div class="text-sm mt-1">{{ $item->quantity }} x Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                                <div class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="border-t border-custom pt-4 space-y-2">
                        <div class="flex justify-between text-secondary">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-secondary">
                            <span>Shipping ({{ $order->shipment?->courier ?? 'Standard' }})</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between font-bold text-lg pt-2 border-t border-custom">
                            <span>Total</span>
                            <span>{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping & Payment Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="bg-surface rounded-lg p-6">
                        <h3 class="font-semibold mb-3">Shipping Address</h3>
                        <p class="font-medium">{{ $order->shipping_name }}</p>
                        <p class="text-secondary">{{ $order->shipping_phone }}</p>
                        <p class="text-secondary mt-1">{{ $order->shipping_address }}</p>

                        @if($order->shipment?->tracking_number)
                            <div class="mt-4 pt-4 border-t border-custom">
                                <p class="text-sm text-secondary">Tracking Number</p>
                                <p class="font-medium">{{ $order->shipment->tracking_number }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-surface rounded-lg p-6">
                        <h3 class="font-semibold mb-3">Payment</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-secondary">Method</span>
                                <span>{{ ucfirst(str_replace('_', ' ', $order->payment?->payment_method ?? '-')) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-secondary">Status</span>
                                <span
                                    class="px-2 py-0.5 rounded text-xs {{ $order->payment?->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($order->payment?->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>

                        @if($order->payment?->payment_method === 'bank_transfer' && $order->payment?->status !== 'paid')
                            <div class="mt-4 pt-4 border-t border-custom">
                                <p class="text-sm text-secondary mb-2">Transfer to:</p>
                                <div class="bg-white p-3 rounded">
                                    <p class="font-medium">Bank BCA</p>
                                    <p class="text-lg font-bold">1234567890</p>
                                    <p class="text-sm text-secondary">a.n. PT Laviade Indonesia</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($order->notes)
                    <div class="bg-surface rounded-lg p-6 mt-6">
                        <h3 class="font-semibold mb-2">Order Notes</h3>
                        <p class="text-secondary">{{ $order->notes }}</p>
                    </div>
                @endif

                <!-- Review Section -->
                @if($order->status === 'completed')
                    <div class="bg-surface rounded-lg p-6 mt-6" id="review">
                        <h3 class="font-semibold mb-4">Write a Review</h3>
                        @foreach($order->items as $item)
                            @php $existingReview = $item->product->reviews->where('user_id', auth()->id())->first(); @endphp
                            @if(!$existingReview)
                                <form action="/api/v1/reviews" method="POST" class="mb-4 pb-4 border-b border-custom last:border-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                                    <div class="flex gap-4 mb-4">
                                        <img src="{{ $item->product_image_url }}" class="w-16 h-16 object-cover rounded">
                                        <div>
                                            <div class="font-medium">{{ $item->product_name }}</div>
                                            <div class="flex gap-1 mt-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" onclick="setRating(this, {{ $i }})"
                                                        class="rating-star text-2xl text-gray-300 hover:text-yellow-500">★</button>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" class="rating-input" value="5">
                                        </div>
                                    </div>
                                    <textarea name="comment" rows="2" placeholder="Share your experience with this product..."
                                        class="w-full px-3 py-2 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300"></textarea>
                                    <button type="submit" class="btn-primary mt-2 text-sm py-2 px-4 rounded">Submit Review</button>
                                </form>
                            @else
                                <div class="mb-4 pb-4 border-b border-custom last:border-0">
                                    <div class="flex gap-4">
                                        <img src="{{ $item->product_image_url }}" class="w-16 h-16 object-cover rounded">
                                        <div>
                                            <div class="font-medium">{{ $item->product_name }}</div>
                                            <div class="flex text-yellow-500 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span>{{ $i <= $existingReview->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                            <p class="text-secondary text-sm mt-1">{{ $existingReview->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function setRating(star, rating) {
                const container = star.closest('div');
                const stars = container.querySelectorAll('.rating-star');
                const input = container.parentElement.querySelector('.rating-input');

                stars.forEach((s, i) => {
                    s.classList.toggle('text-yellow-500', i < rating);
                    s.classList.toggle('text-gray-300', i >= rating);
                });
                input.value = rating;
            }
        </script>
    @endpush
</x-layouts.app>