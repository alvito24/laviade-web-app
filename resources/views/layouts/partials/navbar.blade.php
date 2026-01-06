<nav class="sticky top-0 z-50 bg-white border-b border-custom"
    style="backdrop-filter: blur(10px); background-color: rgba(255,255,255,0.95);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button type="button" id="mobile-menu-btn" class="p-2 rounded-md hover:bg-surface transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <span class="text-2xl font-bold tracking-wider">LAVIADE</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('shop.index') }}" class="nav-link">Shop</a>
                <a href="{{ route('about') }}" class="nav-link">About Us</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="relative hidden sm:block">
                    <input type="text" id="search-input" placeholder="Search..."
                        class="w-48 lg:w-64 px-4 py-2 bg-surface rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                    <div id="search-results"
                        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl hidden z-50 max-h-80 overflow-y-auto">
                    </div>
                </div>

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative p-2 hover:bg-surface rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @auth
                        @if(auth()->user()->cart_items_count > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-black text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                                {{ auth()->user()->cart_items_count }}
                            </span>
                        @endif
                    @endauth
                </a>

                <!-- Profile -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 hover:bg-surface rounded-full transition">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                                class="w-8 h-8 rounded-full object-cover">
                        </button>
                        <div x-show="open" @click.outside="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                            <a href="{{ route('profile.index') }}"
                                class="block px-4 py-2 hover:bg-surface transition">Profil Saya</a>
                            <a href="{{ route('profile.orders') }}"
                                class="block px-4 py-2 hover:bg-surface transition">Pesanan</a>
                            <a href="{{ route('profile.wishlist') }}"
                                class="block px-4 py-2 hover:bg-surface transition">Wishlist</a>
                            <a href="{{ route('profile.addresses') }}"
                                class="block px-4 py-2 hover:bg-surface transition">Alamat</a>
                            <hr class="my-2 border-custom">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 hover:bg-surface transition text-red-600">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="p-2 hover:bg-surface rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-custom">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('shop.index') }}" class="block py-2 px-4 hover:bg-surface rounded transition">Shop</a>
            <a href="{{ route('about') }}" class="block py-2 px-4 hover:bg-surface rounded transition">About Us</a>
            <a href="{{ route('contact') }}" class="block py-2 px-4 hover:bg-surface rounded transition">Contact</a>
            <div class="pt-4">
                <input type="text" placeholder="Search..."
                    class="w-full px-4 py-2 bg-surface rounded-full text-sm focus:outline-none">
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Realtime Search
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;

    searchInput?.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/shop/search?q=${encodeURIComponent(query)}`);
                const products = await response.json();

                if (products.length > 0) {
                    searchResults.innerHTML = products.map(p => `
                    <a href="/shop/${p.slug}" class="flex items-center p-3 hover:bg-surface transition">
                        <img src="${p.image}" alt="${p.name}" class="w-12 h-12 object-cover rounded">
                        <div class="ml-3">
                            <div class="font-medium">${p.name}</div>
                            <div class="text-sm text-secondary">${p.price}</div>
                        </div>
                    </a>
                `).join('');
                    searchResults.classList.remove('hidden');
                } else {
                    searchResults.innerHTML = '<p class="p-4 text-secondary text-center">Tidak ada hasil</p>';
                    searchResults.classList.remove('hidden');
                }
            } catch (e) {
                console.error(e);
            }
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!searchInput?.contains(e.target) && !searchResults?.contains(e.target)) {
            searchResults?.classList.add('hidden');
        }
    });
</script>