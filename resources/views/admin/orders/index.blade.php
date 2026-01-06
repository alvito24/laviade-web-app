<x-layouts.admin title="Orders">
    <div class="mb-6">
        <form class="flex flex-col md:flex-row gap-4 flex-wrap">
            <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none w-full md:w-auto">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="awaiting_payment" {{ request('status') === 'awaiting_payment' ? 'selected' : '' }}>Awaiting
                    Payment</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order number..."
                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black w-full md:w-64">
            <button type="submit"
                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 w-full md:w-auto">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $order->items->count() }} item(s)</td>
                        <td class="px-6 py-4 font-medium">{{ $order->formatted_total }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ in_array($order->status, ['pending', 'awaiting_payment']) ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-blue-100 text-blue-700' : '' }}
                                        ">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->withQueryString()->links() }}
    </div>
</x-layouts.admin>