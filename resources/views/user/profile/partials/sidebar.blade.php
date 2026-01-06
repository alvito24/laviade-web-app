<aside class="lg:w-64 flex-shrink-0">
    <div class="bg-surface rounded-lg p-6">
        <!-- User Info -->
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-custom">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                class="w-12 h-12 rounded-full object-cover">
            <div>
                <div class="font-semibold">{{ auth()->user()->name }}</div>
                <div class="text-sm text-secondary">{{ auth()->user()->email }}</div>
            </div>
        </div>

        <!-- Menu -->
        <nav class="space-y-1">
            <a href="{{ route('profile.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('profile.index') ? 'bg-black text-white' : 'hover:bg-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
            <a href="{{ route('profile.orders') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('profile.orders*') ? 'bg-black text-white' : 'hover:bg-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                My Orders
            </a>
            <a href="{{ route('profile.wishlist') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('profile.wishlist') ? 'bg-black text-white' : 'hover:bg-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                Wishlist
            </a>
            <a href="{{ route('profile.addresses') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('profile.addresses*') ? 'bg-black text-white' : 'hover:bg-white' }} transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Addresses
            </a>
            <hr class="my-4 border-custom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-50 text-red-600 w-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </nav>
    </div>
</aside>