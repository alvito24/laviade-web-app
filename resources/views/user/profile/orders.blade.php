<x-layouts.app title="My Orders">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('user.profile.partials.sidebar')

            <div class="flex-1">
                <div class="bg-surface rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-6">My Orders</h2>

                    @if($orders->count() > 0)
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="bg-white rounded-lg p-4 border border-custom">
                                    <div class="flex flex-col sm:flex-row justify-between gap-4 mb-4">
                                        <div>
                                            <div class="font-semibold">{{ $order->order_number }}</div>
                                            <div class="text-sm text-secondary">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ in_array($order->status, ['pending', 'awaiting_payment']) ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ in_array($order->status, ['processing', 'shipped']) ? 'bg-blue-100 text-blue-700' : '' }}
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 py-3 border-t border-custom">
                                        <div class="flex -space-x-2">
                                            @foreach($order->items->take(3) as $item)
                                                <img src="{{ $item->product_image_url }}" 
                                                     alt="" class="w-12 h-12 rounded object-cover border-2 border-white">
                                            @endforeach
                                            @if($order->items->count() > 3)
                                                <div class="w-12 h-12 rounded bg-gray-200 border-2 border-white flex items-center justify-center text-xs">
                                                    +{{ $order->items->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm text-secondary">{{ $order->items->count() }} item(s)</div>
                                        </div>
                                        <div class="font-bold">{{ $order->formatted_total }}</div>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-3 border-t border-custom">
                                        <a href="{{ route('profile.orders.show', $order->order_number) }}" 
                                           class="btn-secondary text-sm py-2 px-4 rounded">
                                            View Details
                                        </a>
                                        @if($order->status === 'completed' && !$order->reviews()->where('user_id', auth()->id())->exists())
                                            <a href="{{ route('profile.orders.show', $order->order_number) }}#review" 
                                               class="btn-primary text-sm py-2 px-4 rounded">
                                                Write Review
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="font-medium mb-2">No orders yet</h3>
                            <p class="text-secondary mb-4">Start shopping to see your orders here</p>
                            <a href="{{ route('shop.index') }}" class="btn-primary inline-block rounded">Start Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
