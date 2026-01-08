<x-layouts.app title="My Wishlist">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('user.profile.partials.sidebar')

            <div class="flex-1">
                <div class="bg-surface rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-6">My Wishlist ({{ $wishlists->count() }})</h2>

                    @if($wishlists->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($wishlists as $wishlist)
                                @if($wishlist->product)
                                    <div class="bg-white rounded-lg overflow-hidden relative group">
                                        <a href="{{ route('shop.show', $wishlist->product->slug) }}" class="block">
                                            <div class="aspect-square overflow-hidden">
                                                <img src="{{ $wishlist->product->primary_image_url }}"
                                                    alt="{{ $wishlist->product->name }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                            </div>
                                            <div class="p-4">
                                                <h3 class="font-medium text-sm truncate">{{ $wishlist->product->name }}</h3>
                                                <div class="mt-2 flex items-center space-x-2">
                                                    <span
                                                        class="font-semibold">{{ $wishlist->product->formatted_current_price }}</span>
                                                    @if($wishlist->product->hasDiscount())
                                                        <span
                                                            class="text-secondary text-sm line-through">{{ $wishlist->product->formatted_price }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                        <button onclick="removeFromWishlist({{ $wishlist->product_id }})"
                                            class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow hover:bg-red-50 transition">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    {{-- Product has been deleted, show placeholder or auto-remove --}}
                                    <div class="bg-white rounded-lg overflow-hidden relative group opacity-60">
                                        <div class="aspect-square overflow-hidden bg-gray-100 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-medium text-sm truncate text-gray-400">Product no longer available</h3>
                                        </div>
                                        <button onclick="removeFromWishlist({{ $wishlist->product_id }})"
                                            class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow hover:bg-red-50 transition">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <h3 class="font-medium mb-2">Your wishlist is empty</h3>
                            <p class="text-secondary mb-4">Save items you like to your wishlist</p>
                            <a href="{{ route('shop.index') }}" class="btn-primary inline-block rounded">Browse Products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            async function removeFromWishlist(productId) {
                await fetch(`/wishlist/${productId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                location.reload();
            }
        </script>
    @endpush
</x-layouts.app>