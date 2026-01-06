<x-layouts.app title="Shop">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2 text-secondary">
                <li><a href="{{ route('home') }}" class="hover:text-black transition">Home</a></li>
                <li>/</li>
                <li class="text-black">Shop</li>
                @if(isset($currentCategory))
                    <li>/</li>
                    <li class="text-black">{{ $currentCategory->name }}</li>
                @endif
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <form action="{{ route('shop.index') }}" method="GET" id="filter-form">
                    <!-- Categories -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-4">Categories</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('shop.index') }}"
                                    class="block py-1 {{ empty($filters['category_id']) ? 'font-medium text-black' : 'text-secondary hover:text-black' }} transition">
                                    All Products
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('shop.category', $category->slug) }}"
                                        class="block py-1 {{ ($filters['category_id'] ?? null) == $category->id ? 'font-medium text-black' : 'text-secondary hover:text-black' }} transition">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-4">Price Range</h3>
                        <div class="flex items-center space-x-2">
                            <input type="number" name="min_price" placeholder="Min"
                                value="{{ $filters['min_price'] ?? '' }}"
                                class="w-full px-3 py-2 bg-surface rounded text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
                            <span>-</span>
                            <input type="number" name="max_price" placeholder="Max"
                                value="{{ $filters['max_price'] ?? '' }}"
                                class="w-full px-3 py-2 bg-surface rounded text-sm focus:outline-none focus:ring-2 focus:ring-gray-300">
                        </div>
                        <button type="submit" class="mt-3 w-full btn-secondary text-sm py-2 rounded">Apply</button>
                    </div>

                    @if($filters['category_id'] ?? null)
                        <input type="hidden" name="category_id" value="{{ $filters['category_id'] }}">
                    @endif
                </form>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                <!-- Sort & Results -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <p class="text-secondary">
                        Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of
                        {{ $products->total() }} results
                    </p>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-secondary">Sort by:</span>
                        <select id="sort-select"
                            class="px-3 py-2 bg-surface rounded text-sm focus:outline-none cursor-pointer">
                            <option value="latest" {{ ($filters['sort'] ?? 'latest') === 'latest' ? 'selected' : '' }}>
                                Latest</option>
                            <option value="price_low" {{ ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' }}>
                                Price: Low to High</option>
                            <option value="price_high" {{ ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' }}>
                                Price: High to Low</option>
                            <option value="popular" {{ ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' }}>Most
                                Popular</option>
                        </select>
                    </div>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                        @foreach($products as $product)
                            @include('components.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="text-lg font-medium mb-2">Tidak ada produk ditemukan</h3>
                        <p class="text-secondary mb-4">Coba ubah filter atau kata kunci pencarian Anda</p>
                        <a href="{{ route('shop.index') }}" class="btn-primary inline-block rounded">Reset Filter</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('sort-select').addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', this.value);
                window.location.href = url.toString();
            });
        </script>
    @endpush
</x-layouts.app>