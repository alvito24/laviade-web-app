<x-layouts.admin title="Dashboard">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-gray-500 text-sm mb-1">Total Revenue</div>
            <div class="text-2xl font-bold">{{ 'Rp ' . number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="text-green-500 text-xs mt-2">{{ $stats['completed_orders'] }} completed orders</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-gray-500 text-sm mb-1">Active Products</div>
            <div class="text-2xl font-bold">{{ $stats['active_products'] }}</div>
            <div class="text-gray-400 text-xs mt-2">of {{ $stats['total_products'] }} total products</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-gray-500 text-sm mb-1">Total Users</div>
            <div class="text-2xl font-bold">{{ $stats['total_users'] }}</div>
            <div class="text-gray-400 text-xs mt-2">registered customers</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="text-gray-500 text-sm mb-1">Pending Orders</div>
            <div class="text-2xl font-bold text-orange-500">{{ $stats['pending_orders'] }}</div>
            <div class="text-gray-400 text-xs mt-2">{{ $stats['processing_orders'] }} processing</div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white p-6 rounded-lg shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-lg font-semibold mb-4 md:mb-0">Sales Analytics</h2>
            
            <form method="GET" class="flex gap-4">
                <select name="month" class="px-4 py-2 border rounded-lg focus:outline-none" onchange="this.form.submit()">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="px-4 py-2 border rounded-lg focus:outline-none" onchange="this.form.submit()">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <div class="relative h-80">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Recent Orders & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="font-semibold">Recent Orders</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody class="divide-y">
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $order->order_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $order->user->name }}</td>
                                <td class="px-6 py-4">{{ $order->formatted_total }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-gray-50 border-t text-center">
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:underline">View All Orders</a>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="font-semibold">Top Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody class="divide-y">
                        @forelse($topProducts as $product)
                            <tr>
                                <td class="px-6 py-4 w-12">
                                    <img src="{{ $product->primary_image_url }}" class="w-10 h-10 rounded object-cover">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $product->category->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-bold">{{ $product->order_items_count }}</div>
                                    <div class="text-xs text-gray-500">sold</div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No data available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: @json($chartData['revenue']),
                    borderColor: '#1a1a1a',
                    backgroundColor: 'rgba(26, 26, 26, 0.1)',
                    yAxisID: 'y',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Orders',
                    data: @json($chartData['orders']),
                    borderColor: '#9ca3af',
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    yAxisID: 'y1',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        grid: {
                            color: '#e5e5e5'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
    @endpush
</x-layouts.admin>
