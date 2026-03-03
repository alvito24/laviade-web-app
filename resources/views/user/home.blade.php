<x-layouts.app title="Home">
    <!-- Hero Slider -->
    <section class="relative">
        <div id="hero-slider" class="relative overflow-hidden">
            @forelse($banners as $index => $banner)
                <div class="hero-slide {{ $index === 0 ? '' : 'hidden' }}" data-index="{{ $index }}">
                    <div class="relative h-[70vh] min-h-[500px] bg-cover bg-center"
                        style="background-image: url('{{ asset('storage/' . $banner->image) }}')">
                        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white px-4">
                                @if($banner->title)
                                    <h1 class="text-4xl md:text-6xl font-bold mb-4 tracking-wide">{{ $banner->title }}</h1>
                                @endif
                                @if($banner->subtitle)
                                    <p class="text-lg md:text-xl mb-8 opacity-90">{{ $banner->subtitle }}</p>
                                @endif
                                @if($banner->cta_link)
                                    <a href="{{ $banner->cta_link }}"
                                        class="btn-primary inline-block rounded-full px-8 py-3 text-lg">
                                        {{ $banner->cta_text ?? 'Shop Now' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="relative h-[70vh] min-h-[500px] bg-gradient-to-br from-gray-900 to-gray-700">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            <h1 class="text-4xl md:text-6xl font-bold mb-4 tracking-wide">LAVIADE</h1>
                            <p class="text-lg md:text-xl mb-8 opacity-90">Fashion Streetwear Modern</p>
                            <a href="{{ route('shop.index') }}"
                                class="btn-primary inline-block rounded-full px-8 py-3 text-lg">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($banners->count() > 1)
            <!-- Slider Controls -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach($banners as $index => $banner)
                    <button class="slider-dot w-3 h-3 rounded-full transition {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}"
                        data-index="{{ $index }}"></button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- New Releases -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">New Release</h2>
            <a href="{{ route('shop.index', ['sort' => 'latest']) }}"
                class="text-secondary hover:text-black transition flex items-center">
                View All
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($newArrivals as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <!-- Best Sellers -->
    <section
        class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto bg-surface -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold">Best Seller</h2>
                <a href="{{ route('shop.index', ['sort' => 'popular']) }}"
                    class="text-secondary hover:text-black transition flex items-center">
                    View All
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($bestSellers as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Banner -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-surface rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Free Shipping</h3>
                <p class="text-secondary text-sm">Gratis ongkir untuk pembelian di atas Rp 500.000</p>
            </div>
            <div class="text-center p-6">
                <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-surface rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Easy Returns</h3>
                <p class="text-secondary text-sm">Pengembalian mudah dalam 30 hari</p>
            </div>
            <div class="text-center p-6">
                <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center bg-surface rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="font-semibold mb-2">Secure Payment</h3>
                <p class="text-secondary text-sm">Pembayaran 100% aman dan terpercaya</p>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Auto Slider
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('hidden', i !== index);
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-white', i === index);
                    dot.classList.toggle('bg-white/50', i !== index);
                });
                currentSlide = index;
            }

            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    showSlide(parseInt(dot.dataset.index));
                });
            });

            if (slides.length > 1) {
                setInterval(() => {
                    showSlide((currentSlide + 1) % slides.length);
                }, 5000);
            }
        </script>
    @endpush
</x-layouts.app>