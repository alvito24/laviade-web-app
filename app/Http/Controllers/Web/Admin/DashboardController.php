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
        $filterMode = request('filter_mode', 'month');
        $year = request('year', now()->year);
        $month = request('month', now()->month);

        $orderQuery = Order::query();

        if ($filterMode === 'year') {
            $orderQuery->whereYear('created_at', $year);
        } elseif ($filterMode === 'month') {
            $orderQuery->whereYear('created_at', $year)
                       ->whereMonth('created_at', $month);
        }

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_users' => User::count(),
            'total_orders' => (clone $orderQuery)->count(),
            'pending_orders' => (clone $orderQuery)->where('status', 'pending')->count(),
            'processing_orders' => (clone $orderQuery)->whereIn('status', ['awaiting_payment', 'payment_confirmed', 'processing'])->count(),
            'completed_orders' => (clone $orderQuery)->where('status', 'completed')->count(),
            'total_revenue' => (clone $orderQuery)->where('status', 'completed')->sum('total'),
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
        $chartData = [
            'labels' => [],
            'revenue' => [],
        ];

        $chartOrderQuery = Order::whereNotIn('status', ['cancelled', 'payment_failed']);

        if ($filterMode === 'all') {
            $yearlyRevenue = (clone $chartOrderQuery)
                ->selectRaw('YEAR(created_at) as year, SUM(total) as revenue')
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            foreach ($yearlyRevenue as $data) {
                $chartData['labels'][] = (string) $data->year;
                $chartData['revenue'][] = (int) $data->revenue;
            }
        } elseif ($filterMode === 'year') {
            $monthlyRevenue = (clone $chartOrderQuery)
                ->whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            for ($i = 1; $i <= 12; $i++) {
                $monthData = $monthlyRevenue->firstWhere('month', $i);
                $chartData['labels'][] = date('F', mktime(0, 0, 0, $i, 1));
                $chartData['revenue'][] = $monthData ? (int) $monthData->revenue : 0;
            }
        } else {
            $dailyRevenue = (clone $chartOrderQuery)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->selectRaw('DATE(created_at) as date, SUM(total) as revenue')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $daysInMonth = \Carbon\Carbon::createFromDate($year, $month)->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = \Carbon\Carbon::createFromDate($year, $month, $i)->format('Y-m-d');
                $dayData = $dailyRevenue->firstWhere('date', $date);

                $chartData['labels'][] = (string) $i;
                $chartData['revenue'][] = $dayData ? (int) $dayData->revenue : 0;
            }
        }

        $availableYears = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        if (empty($availableYears)) {
            $availableYears = [now()->year];
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'chartData', 'availableYears', 'year', 'month', 'filterMode'));
    }
}
