<x-layouts.app :title="$product->name">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2 text-secondary">
                <li><a href="{{ route('home') }}" class="hover:text-black transition">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('shop.index') }}" class="hover:text-black transition">Shop</a></li>
                <li>/</li>
                <li><a href="{{ route('shop.category', $product->category->slug) }}"
                        class="hover:text-black transition">{{ $product->category->name }}</a></li>
                <li>/</li>
                <li class="text-black truncate max-w-[150px]">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Product Images -->
            <div>
                <!-- Main Image -->
                <div class="aspect-square bg-surface rounded-lg overflow-hidden mb-4">
                    <img id="main-image" src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover">
                </div>

                <!-- Thumbnails -->
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($product->images as $index => $image)
                            <button type="button" onclick="document.getElementById('main-image').src='{{ $image->image_url }}'"
                                class="aspect-square bg-surface rounded overflow-hidden hover:ring-2 hover:ring-black transition">
                                <img src="{{ $image->image_url }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-4">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="flex items-center space-x-4 mb-6">
                    <span class="text-2xl font-bold">{{ $product->formatted_current_price }}</span>
                    @if($product->hasDiscount())
                        <span class="text-lg text-secondary line-through">{{ $product->formatted_price }}</span>
                        <span
                            class="bg-red-500 text-white text-sm px-2 py-1 rounded">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>

                <!-- Rating -->
                @if($product->review_count > 0)
                    <div class="flex items-center mb-6">
                        <div class="flex text-yellow-500">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'fill-current' : 'fill-gray-300' }}"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-secondary">({{ $product->review_count }} reviews)</span>
                    </div>
                @endif

                <!-- Description -->
                @if($product->short_description)
                    <p class="text-secondary mb-6">{{ $product->short_description }}</p>
                @endif

                <!-- Size Selection -->
                @if($product->sizes && count($product->sizes) > 0)
                    <div class="mb-6">
                        <label class="block font-medium mb-3">Select Size</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->sizes as $size)
                                <button type="button" onclick="selectSize('{{ $size }}')"
                                    class="size-btn px-4 py-2 border border-custom rounded hover:border-black transition">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" id="selected-size" value="">
                    </div>
                @endif

                <!-- Color Selection -->
                @if($product->colors && count($product->colors) > 0)
                    <div class="mb-6">
                        <label class="block font-medium mb-3">Select Color</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->colors as $color)
                                <button type="button" onclick="selectColor('{{ $color }}')"
                                    class="color-btn px-4 py-2 border border-custom rounded hover:border-black transition">
                                    {{ $color }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" id="selected-color" value="">
                    </div>
                @endif

                <!-- Stock Info -->
                <div class="mb-6">
                    @if($product->isInStock())
                        <span class="text-green-600 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            In Stock ({{ $product->stock }} available)
                        </span>
                    @else
                        <span class="text-red-600">Out of Stock</span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    @auth
                        <button id="buy-now-btn" onclick="buyNow()"
                            class="btn-primary flex-1 py-4 rounded-lg font-semibold text-center" {{ !$product->isInStock() ? 'disabled' : '' }}>
                            Buy Now
                        </button>
                        <button id="add-to-cart-btn" onclick="addToCart()"
                            class="btn-secondary flex-1 py-4 rounded-lg font-semibold" {{ !$product->isInStock() ? 'disabled' : '' }}>
                            Add to Cart
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary flex-1 py-4 rounded-lg font-semibold text-center">
                            Login to Buy
                        </a>
                    @endauth
                </div>

                <!-- Product Details -->
                <div class="border-t border-custom pt-6">
                    <h3 class="font-semibold mb-4">Product Details</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex">
                            <dt class="w-32 text-secondary">SKU</dt>
                            <dd>{{ $product->sku }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-32 text-secondary">Category</dt>
                            <dd>{{ $product->category->name }}</dd>
                        </div>
                        @if($product->material)
                            <div class="flex">
                                <dt class="w-32 text-secondary">Material</dt>
                                <dd>{{ $product->material }}</dd>
                            </div>
                        @endif
                        @if($product->weight)
                            <div class="flex">
                                <dt class="w-32 text-secondary">Weight</dt>
                                <dd>{{ $product->weight }}g</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                @if($product->description)
                    <div class="border-t border-custom pt-6 mt-6">
                        <h3 class="font-semibold mb-4">Description</h3>
                        <div class="prose text-secondary text-sm">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reviews Section -->
        <section class="mt-16 border-t border-custom pt-8">
            <h2 class="text-xl font-bold mb-6">Reviews ({{ $product->review_count }})</h2>

            @if($product->reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($product->reviews->take(5) as $review)
                        <div class="bg-surface rounded-lg p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}"
                                        class="w-10 h-10 rounded-full">
                                    <div class="ml-3">
                                        <div class="font-medium">{{ $review->user->name }}</div>
                                        <div class="text-sm text-secondary">{{ $review->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="flex text-yellow-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'fill-gray-300' }}"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-secondary">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-secondary text-center py-8">Belum ada review untuk produk ini</p>
            @endif
        </section>

        <!-- You May Also Like -->
        <section class="mt-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold">You May Also Like</h2>
                <a href="{{ route('shop.index', ['sort' => 'popular']) }}"
                    class="text-secondary hover:text-black transition">View All</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($bestSellers as $related)
                    @include('components.product-card', ['product' => $related])
                @endforeach
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            const productId = {{ $product->id }};

            function selectSize(size) {
                document.querySelectorAll('.size-btn').forEach(btn => {
                    btn.classList.remove('bg-black', 'text-white', 'border-black');
                });
                event.target.classList.add('bg-black', 'text-white', 'border-black');
                document.getElementById('selected-size').value = size;
            }

            function selectColor(color) {
                document.querySelectorAll('.color-btn').forEach(btn => {
                    btn.classList.remove('bg-black', 'text-white', 'border-black');
                });
                event.target.classList.add('bg-black', 'text-white', 'border-black');
                document.getElementById('selected-color').value = color;
            }

            async function addToCart() {
                const size = document.getElementById('selected-size')?.value;
                const color = document.getElementById('selected-color')?.value;

                try {
                    const response = await fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1,
                            size: size,
                            color: color,
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        alert('Produk ditambahkan ke keranjang');
                        location.reload();
                    }
                } catch (e) {
                    console.error(e);
                }
            }

            async function buyNow() {
                await addToCart();
                window.location.href = '/checkout';
            }
        </script>
    @endpush
</x-layouts.app>