<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::whereIn('status', ['awaiting_payment', 'payment_confirmed', 'processing'])->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();

        // --- Chart Data Logic ---
        $year = request('year', now()->year);
        $month = request('month', now()->month);

        $dailyRevenue = Order::whereNotIn('status', ['cancelled', 'payment_failed'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartData = [
            'labels' => [],
            'revenue' => [],
            'orders' => []
        ];

        $daysInMonth = \Carbon\Carbon::createFromDate($year, $month)->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = \Carbon\Carbon::createFromDate($year, $month, $i)->format('Y-m-d');
            $dayData = $dailyRevenue->firstWhere('date', $date);

            $chartData['labels'][] = (string) $i;
            $chartData['revenue'][] = $dayData ? (int) $dayData->revenue : 0;
            $chartData['orders'][] = $dayData ? (int) $dayData->count : 0;
        }

        $availableYears = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        if (empty($availableYears))
            $availableYears = [now()->year];

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'chartData', 'availableYears', 'year', 'month'));
    }
}
