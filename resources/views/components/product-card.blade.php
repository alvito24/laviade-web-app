<a href="{{ route('shop.show', $product->slug) }}" class="card group block">
    <div class="relative aspect-square overflow-hidden">
        <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

        @if($product->hasDiscount())
            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2 py-1 rounded">
                -{{ $product->discount_percent }}%
            </span>
        @endif

        @if($product->is_new_arrival)
            <span class="absolute top-3 right-3 bg-black text-white text-xs px-2 py-1 rounded">
                NEW
            </span>
        @endif

        <!-- Wishlist Button -->
        @auth
            <button type="button" onclick="event.preventDefault(); toggleWishlist({{ $product->id }}, this)"
                class="absolute bottom-3 right-3 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-surface transition opacity-0 group-hover:opacity-100">
                <svg class="w-5 h-5 {{ auth()->user()->hasProductInWishlist($product->id) ? 'fill-red-500 text-red-500' : 'fill-none text-gray-600' }}"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        @endauth
    </div>

    <div class="p-4">
        <h3 class="font-medium text-sm md:text-base truncate">{{ $product->name }}</h3>
        <div class="mt-2 flex items-center space-x-2">
            <span class="font-semibold">{{ $product->formatted_current_price }}</span>
            @if($product->hasDiscount())
                <span class="text-secondary text-sm line-through">{{ $product->formatted_price }}</span>
            @endif
        </div>
        @if($product->review_count > 0)
            <div class="mt-2 flex items-center text-sm text-secondary">
                <span class="text-yellow-500">★</span>
                <span class="ml-1">{{ number_format($product->average_rating, 1) }}</span>
                <span class="ml-1">({{ $product->review_count }})</span>
            </div>
        @endif
    </div>
</a>

@once
    @push('scripts')
        <script>
            async function toggleWishlist(productId, button) {
                try {
                    const response = await fetch(`/wishlist/toggle/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();

                    const svg = button.querySelector('svg');
                    if (data.added) {
                        svg.classList.add('fill-red-500', 'text-red-500');
                        svg.classList.remove('fill-none', 'text-gray-600');
                    } else {
                        svg.classList.remove('fill-red-500', 'text-red-500');
                        svg.classList.add('fill-none', 'text-gray-600');
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        </script>
    @endpush
@endonce