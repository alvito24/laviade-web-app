<x-layouts.admin title="User Details: {{ $user->name }}">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white p-6 rounded-lg shadow-sm h-fit">
            <div class="flex items-center gap-4 mb-6">
                <div
                    class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-2xl font-bold text-gray-500">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                    <div class="text-gray-500">{{ $user->email }}</div>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Phone</span>
                    <span>{{ $user->phone ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Status</span>
                    <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-500">Joined</span>
                    <span>{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="w-full text-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Edit User</a>
            </div>
        </div>

        <!-- Activity & Orders -->
        <div class="md:col-span-2 space-y-6">
            <!-- Addresses -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold mb-4">Saved Addresses</h3>
                @forelse($user->addresses as $address)
                    <div class="border p-4 rounded-lg mb-2 {{ $address->is_primary ? 'border-black' : 'border-gray-200' }}">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $address->label }}</span>
                            @if($address->is_primary) <span
                            class="text-xs bg-black text-white px-2 py-0.5 rounded">Primary</span> @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $address->recipient_name }} | {{ $address->phone }}<br>
                            {{ $address->full_address }}<br>
                            {{ $address->district }}, {{ $address->city }}, {{ $address->province }}
                            {{ $address->postal_code }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">No addresses saved.</p>
                @endforelse
            </div>

            <!-- Order History -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold mb-4">Order History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Order #</th>
                                <th class="px-4 py-2 text-left">Date</th>
                                <th class="px-4 py-2 text-left">Total</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->orders as $order)
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ $order->formatted_total }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100">{{ $order->status }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="text-blue-600 text-xs hover:underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No orders placed yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>